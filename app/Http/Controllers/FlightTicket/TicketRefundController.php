<?php

namespace App\Http\Controllers\FlightTicket;

use App\User;
use App\PurchaseEntry;
use Illuminate\Http\Request;
use App\Services\AgentService;
use App\Services\CreditService;
use App\Services\RefundService;
use App\Services\SupplierService;
use App\Models\FlightTicket\Agent;
use App\Models\FlightTicket\Owner;
use Illuminate\Support\Facades\DB;
use App\Mail\CancelRequestApproval;
use App\Models\AgentCreditShellLog;
use App\Models\CancellationRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\FlightTicket\Credits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\FlightTicket\Supplier;
use Illuminate\Support\Facades\Cache;
use App\Models\FlightTicket\BookTicket;
use App\Mail\SupplierCancelRequestApproval;
use App\Models\FlightTicket\AirTicketRefunds;
use App\Models\FlightTicket\BookTicketSummary;
use App\Models\FlightTicket\Accounts\SupplierTransaction;

class TicketRefundController extends Controller
{
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'book_ticket_id' => 'required|integer'
        ]);

        $data = BookTicket::find($request->book_ticket_id);
        $cancellationCharge = RefundService::getAutoCancellationCharge($request->book_ticket_id);

        return view('flight-tickets.refunds.create', compact('data','cancellationCharge'));
    }



    public function refund(Request $request)
    {
        $this->validate($request, [
            'cancel_request_id' => 'required|integer'
        ]);

        $cancelrequest = CancellationRequest::find($request->cancel_request_id);
        if($cancelrequest->status == 2 || $cancelrequest->status == 3) {
            abort(403, "Ticket can't be refunded");
        }
        $data = BookTicket::find($cancelrequest->book_id);
        $passenger_ids = $cancelrequest->passenger_ids;

            if (is_string($passenger_ids)) {
                $passenger_ids = json_decode($passenger_ids, true);
            }


        return view('flight-tickets.refunds.refund', compact('data','passenger_ids'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $this->validate($request, [
            'refund'           => 'required|array',
            'ticket_id'        => 'required',
            'agent_id'         => 'required',
            'remarks'          => 'required',
            'totalAmount'      => 'required',
            'childCharge'      => 'required',
            'infantCharge'     => 'required',
            'TotalSaleAmount'  => 'required',
            'refund_pax_price' => 'required', // agent per pax price charge only adult
            'total_refund'     => 'required', // refund_pax_price * count of refund (only adult and child)
            'balance'          => 'required', // agent balance that need to be added to agent transaction and updated balance.
            'supplier_refund_pax_price' => 'required', // supplier per pax price charge only adult 
            'supplier_total_refund'=> 'required', // supplier_refund_pax_price * count of refund (only adult and child)
            'supplier_balance'=> 'required' // supplier_refund_pax_price * count of refund (only adult and child)
        ]);

        //
       
      
        try {
            $old_ticket_id = Cache::get("refund_ticket_id", null);

            if($old_ticket_id === $request->ticket_id ){
                $request->session()->flash('error', 'Refund couldn\'t be processed now wait for 5 ... ');
                return redirect(route('sales.show', $request->ticket_id));
            }else{
                Cache::put("refund_ticket_id",$request->ticket_id, 300);
            }

            $ticketRefund = AirTicketRefunds::where('book_ticket_id', $request->ticket_id)
                                            ->where('passenger_ids', json_encode($request->refund))
                                            ->count();
                           
            if($ticketRefund > 0) {
                //dd($ticketRefund);
                $request->session()->flash('error', 'Refund couldn\'t be processed now as its already refunded ... ');
                return redirect(route('sales.show', $request->ticket_id));
            }

            $booking_passengers   = BookTicketSummary::find($request->refund);
            foreach ($booking_passengers as $key => $val) {
                if($val->is_refund == 1 ) {
                    $request->session()->flash('error', 'Refund couldn\'t be processed now as passenger is already refunded');
                    return redirect(route('sales.show', $request->ticket_id));
                }
            }

            $bookingTicketDetail  = BookTicket::find($request->ticket_id);
            $purchaseEntryDetail  = PurchaseEntry::where('id', $bookingTicketDetail->purchase_entry_id)->first();
            $total_pax_count      = BookTicketSummary::where('book_ticket_id' , $request->ticket_id)->count();
            $total_refunded_count = BookTicketSummary::where('book_ticket_id' , $request->ticket_id)->where('is_refund', 1)->count();

            //condition checked only for refundable tickets
            if($purchaseEntryDetail->isRefundable) {
                $cancellationCharge = RefundService::getAutoCancellationCharge($request->ticket_id);
                if($request->refund_pax_price > $cancellationCharge) {
                    $request->session()->flash('error', 'Refund couldn\'t be processed now as charge given is greated than actual refundable charge');
                    return redirect(route('sales.show', $request->ticket_id));
                }
            }


            DB::beginTransaction();
            if($booking_passengers->count() == ($total_pax_count - $total_refunded_count)) {
                $bookingTicketDetail->status = 4;
            }else{
                $bookingTicketDetail->status = 3;
            }

            $bookingTicketDetail->save();

            foreach ($booking_passengers as $key => $val) {
                $val->update([
                    'is_refund' => 1
                ]);
            }
            // check the airline of the ticket;

            if($request->wallet_type == 2){
                $account_transaction  = [
                    'agent_id'     => $request->agent_id,
                    'type'         => 9, // gofirst wallet
                    'ticket_id'    => $request->ticket_id,
                    'amount'       => $request->balance,
                    'remarks'      => 'Refunds of Ticket for ' . ( $request->adult_count + $request->child )  . ' Adult '. $request->infant_count.' Infant passenger',
                    'owner_id'     => Auth::id(),
                    'reference_no' => CreditService::generateReferenceNo(),
                ];

                AgentService::updateCreditshell($request->agent_id,$request->balance);
                $AgentCreditShellLogResp = AgentCreditShellLog::create([
                    'agent_id' => $request->agent_id,
                    'amount'       => $request->balance,
                    'book_ticket_id' => $request->ticket_id,
                    'airline_id' => 3
                ]);
               
            }elseif($request->wallet_type == 1){
                $account_transaction  = [
                    'agent_id'     => $request->agent_id,
                    'type'         => 4,
                    'ticket_id'    => $request->ticket_id,
                    'amount'       => $request->balance,
                    'remarks'      => 'Refunds of Ticket for ' . ( $request->adult_count + $request->child )  . ' Adult '. $request->infant_count.' Infant passenger',
                    'owner_id'     => Auth::id(),
                    'reference_no' => CreditService::generateReferenceNo(),
                ];
                AgentService::updateOpeningBalance($request->agent_id, 1, $request->balance);
                // Update credit balance based on credit limit for agent
                $agent = Agent::where('id', $request->agent_id)->first();
                if($agent->credit_limit > 0) {
                    AgentService::updateCreditBalanceBasedOnCreditLimt($request->agent_id, 1, $request->balance);
                }
            }

            $creditResp = Credits::create($account_transaction);

          
            $total_adult = $request->adult_count + $request->child_count;
            $purchaseEntryDetail->decrement('sold',  $total_adult);
            $purchaseEntryDetail->increment('available', $total_adult);
            //Decrease balance of the supplier
            $opening_bal = 0;
            $owner       = Owner::find($purchaseEntryDetail->owner_id);
            if($owner->opening_balance) {
                $opening_bal = $owner->opening_balance;
            }
            $new_balance = $opening_bal - $request->supplier_balance;
            $owner->opening_balance =  $new_balance;
            $owner->save();

            // $markups = DB::table('airline_markups')
            //     ->where('agent_id', $request->agent_id)
            //     ->where('airline_id', $purchaseEntryDetail->airline_id)
            //     ->where('status', 1)
            //     ->get();

            // $additional_price = 0;

            // if(isset($markups[0])) {
            //     $additional_price = $markups[0]->amount;
            // }

            // $total_markup = $bookingTicketDetail->agent_markup;

            $suplier_transaction  = [
                'supplier_id'  => $purchaseEntryDetail->owner_id,
                'type'         => 2,
                'ticket_id'    => $request->ticket_id,
                // 'amount'       => $request->balance - $total_markup,
                'amount'       => $request->supplier_balance,
                'balance'      => $new_balance,
                'remarks'      => 'Refunds of Ticket for ' . ( $request->adult_count + $request->child )  . ' Adult '. $request->infant_count.' Infant passenger',
                'owner_id'     => Auth::id(),
                'reference_no' => SupplierService::generateReferenceNo(),
            ];
            $supplier_transaction_details = SupplierTransaction::create($suplier_transaction);
           
            $refunds_data = [
                'agent_id'               => $request->agent_id,
                'book_ticket_id'         => $request->ticket_id,
                'passenger_ids'          => $request->refund,
                'pax_cost'               => $request->refund_pax_price,
                'total_refund'           => $request->balance,
                'remarks'                => $request->remarks,
                'pax'                    => count($request->refund),
                'owner_id'               => Auth::id(),
                'adult'                  => $request->adult_count,
                'child'                  => $request->child_count,
                'infant'                 => $request->infant_count,
                'wallet_type'            => $request->wallet_type,
                'account_transaction_id' => $creditResp->id,
                'supplier_refund_pax_price' => $request->supplier_refund_pax_price,
                'supplier_total_refund'=> $request->supplier_total_refund,
                'supplier_balance'=> $request->supplier_balance,
                'supplier_transaction_id' => $supplier_transaction_details->id


            ];
            $resp =  AirTicketRefunds::create($refunds_data);

            try{
                $subject = "TICKET REFUND APPROVAL OF " . $bookingTicketDetail->bill_no;
                if($owner->is_third_party){
                    Mail::to($owner->email)->cc('support@vishaltravels.in')->send(new SupplierCancelRequestApproval($owner, $subject, $refunds_data, $bookingTicketDetail, $booking_passengers));
                }
            }catch(\Exception $e){
                Log::error("MAIL ERROR - ".  $e->getMessage());
            }

            DB::commit();
        }catch(\Exception $e) {
            DB::rollback();
            $request->session()->flash('error', $e->getMessage());
            return redirect(route('sales.show', $request->ticket_id));
        }

        $request->session()->flash('success', 'Successfully Refund');
        return redirect(route('sales.show', $request->ticket_id));
    }



    public function seatsLive(Request $request)
    {
        $user_id = Auth::user()->id;
        $cancelRequest = CancellationRequest::find($request->id);

        if ($cancelRequest->status == 2 || $cancelRequest->status == 3 || $cancelRequest->status == 4) {
            return response()->json(['success' => false, 'message' => 'Cannot live the seats']);
        }

        $bookingTicketDetail  = BookTicket::find($cancelRequest->book_id);
        $purchaseEntryDetail  = \App\PurchaseEntry::where('id', $bookingTicketDetail->purchase_entry_id)->first();

        $total_pax = $cancelRequest->passenger_ids;
        if (is_string($total_pax)) {
            $total_pax = json_decode($total_pax, true);
        }

        $total_adult = [];
        foreach ($total_pax as $value) {
            $summary = BookTicketSummary::find($value);
            if ($summary->type == 1 || $summary->type == 2) {
                $total_adult[] = $value;
            }
        }

        DB::beginTransaction();
        try {
            BookTicketSummary::whereIn('id', $total_pax)->update(['is_refund' => 2]); 

            $purchaseEntryDetail->decrement('sold', count($total_adult));
            $purchaseEntryDetail->increment('available', count($total_adult));

            $cancelRequest->status = 4; 
            $cancelRequest->owner_id = $user_id;
            $cancelRequest->save();

            $bookingTicketDetail->status = 2;
            $bookingTicketDetail->save();

            DB::commit();

            $html = view('modal.cancel-rejection-remarks')->with('cancel_request_id', $request->id)->render();
            return response()->json(['success' => true, 'message' => $html]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function refundBooking(Request $request) {

        $this->validate($request, [
            'refund'           => 'required|array',
            'ticket_id'        => 'required',
            'agent_id'         => 'required',
            'remarks'          => 'required',
            'totalAmount'      => 'required',
            'childCharge'      => 'required',
            'infantCharge'     => 'required',
            'TotalSaleAmount'  => 'required',
            'refund_pax_price' => 'required', // agent per pax price charge only adult
            'total_refund'     => 'required', // refund_pax_price * count of refund (only adult and child)
            'balance'          => 'required', // agent balance that need to be added to agent transaction and updated balance.
            'supplier_refund_pax_price' => 'required', // supplier per pax price charge only adult 
            'supplier_total_refund'=> 'required', // supplier_refund_pax_price * count of refund (only adult and child)
            'supplier_balance'=> 'required' // supplier_refund_pax_price * count of refund (only adult and child)
        ]);

        $old_ticket_id = Cache::get("refund_ticket_id", null);
        // cache check and setup
        if($old_ticket_id === $request->ticket_id ){
            $request->session()->flash('error', 'Refund couldn\'t be processed now ... ');
            return redirect(route('sales.show', $request->ticket_id));
        }else{
            Cache::put("refund_ticket_id",$request->ticket_id, 300);
        }




        $user_id = Auth::user()->id;
        $booking_passengers = BookTicketSummary::find($request->refund);

        foreach ($booking_passengers as $key => $val) {
            if($val->is_refund == 1 ) {
                $request->session()->flash('error', 'Refund couldn\'t be processed now as passenger is already refunded');
                return redirect(route('sales.show', $request->ticket_id));
            }
        }
        $bookingTicketDetail  = BookTicket::find($request->ticket_id);
        $purchaseEntryDetail  = PurchaseEntry::where('id', $bookingTicketDetail->purchase_entry_id)->first();
        $cancelRequest = CancellationRequest::find($request->cancel_id);
        $total_pax_count = BookTicketSummary::where('book_ticket_id' , $request->ticket_id)->count();
        $total_refunded_count = BookTicketSummary::where('book_ticket_id' , $request->ticket_id)->where('is_refund', 1)->count();


        if($cancelRequest->status == 1 || $cancelRequest->status == 3) {
            $total_adult = $request->adult_count + $request->child_count;
            $purchaseEntryDetail->decrement('sold',  $total_adult);
            $purchaseEntryDetail->increment('available', $total_adult);
        }

        $cancelRequest->status = 2; // change status to approved
        $cancelRequest->owner_id = $user_id;
        $cancelRequest->save();

        if($booking_passengers->count() == ($total_pax_count - $total_refunded_count)) {
            $bookingTicketDetail->status = 4;
        }else{
            $bookingTicketDetail->status = 3;
        }

        $bookingTicketDetail->save();

        foreach ($booking_passengers as $key => $val) {
            $val->update([
                'is_refund' => 1
            ]);
        }
        if($request->wallet_type == 2) {
            $account_transaction  = [
                'agent_id'     => $request->agent_id,
                'type'         => 9, // gofirst wallet
                'ticket_id'    => $request->ticket_id,
                'amount'       => $request->balance,
                'remarks'      => 'Refunds of Ticket for ' . ( $request->adult_count + $request->child )  . ' Adult '. $request->infant_count.' Infant passenger',
                'owner_id'     => Auth::id(),
                'reference_no' => CreditService::generateReferenceNo(),
            ];

            AgentService::updateCreditshell($request->agent_id,$request->balance);
            AgentCreditShellLog::create([
                'agent_id' => $request->agent_id,
                'amount'       => $request->balance,
                'book_ticket_id' => $request->ticket_id,
                'airline_id' => 3
            ]);

        }elseif($request->wallet_type == 1){
            $account_transaction = [
                'agent_id' => $request->agent_id,
                'type' => 4,
                'ticket_id' => $request->ticket_id,
                'amount' => $request->balance,
                'remarks' => 'Refunds of Ticket for ' . ($request->adult_count + $request->child) . ' Adult ' . $request->infant_count . ' Infant passenger',
                'owner_id' => Auth::id(),
                'reference_no' => CreditService::generateReferenceNo(),
            ];
            AgentService::updateOpeningBalance($request->agent_id, 1, $request->balance);
            // Update credit balance based on credit limit for agent
            $agent = Agent::where('id', $request->agent_id)->first();
            if($agent->credit_limit > 0) {
                AgentService::updateCreditBalanceBasedOnCreditLimt($request->agent_id, 1, $request->balance);
            }
        }

        $creditResp = Credits::create($account_transaction);

        $total_markup = $bookingTicketDetail->agent_markup;
        //Decrease balance of the supplier
        $opening_bal = 0;
        $owner = Owner::find($purchaseEntryDetail->owner_id);
        if($owner->opening_balance) {
            $opening_bal = $owner->opening_balance;
        }
        $new_balance = $opening_bal - $request->supplier_balance - $total_markup;
        $owner->opening_balance =  $new_balance;
        $owner->save();

        $suplier_transaction  = [
            'supplier_id'  => $purchaseEntryDetail->owner_id,
            'type'         => 2,
            'ticket_id'    => $request->ticket_id,
            'amount'       => $request->supplier_balance - $total_markup,
            'balance'      => $new_balance,
            'remarks'      => 'Refunds of Ticket for ' . ( $request->adult_count + $request->child )  . ' Adult '. $request->infant_count.' Infant passenger',
            'owner_id'     => Auth::id(),
            'reference_no' => SupplierService::generateReferenceNo(),
        ];
        $supplier_transaction_details =  SupplierTransaction::create($suplier_transaction);
        $refunds_data = [
            'agent_id' => $request->agent_id,
            'book_ticket_id' => $request->ticket_id,
            'passenger_ids' => $request->refund,
            'pax_cost' => $request->refund_pax_price,
            'total_refund' => $request->balance,
            'remarks' => $request->remarks,
            'pax' => count($request->refund),
            'owner_id' => Auth::id(),
            'adult' => $request->adult_count,
            'child' => $request->child_count,
            'infant' => $request->infant_count,
            'account_transaction_id' => $creditResp->id,
            'supplier_refund_pax_price' => $request->supplier_refund_pax_price,
            'supplier_total_refund'=> $request->supplier_total_refund,
            'supplier_balance'=> $request->supplier_balance,
            'supplier_transaction_id' => $supplier_transaction_details->id
        ];

        $resp =  AirTicketRefunds::create($refunds_data);
        try{
            $agent = Agent::find($request->agent_id);
            $subject = "CANCELLATION REQUEST DETAILS OF " . $bookingTicketDetail->bill_no;
            $message = $request->remarks;
            Mail::to($agent->email)->cc('support@vishaltravels.in')->send(new CancelRequestApproval($agent, $cancelRequest, $subject, $message , $refunds_data, $bookingTicketDetail, $booking_passengers));

            if($owner->is_third_party){
                Mail::to($owner->email)->cc('support@vishaltravels.in')->send(new SupplierCancelRequestApproval($owner, $subject, $refunds_data, $bookingTicketDetail, $booking_passengers));
            }
        }catch(\Exception $e){
            Log::error("MAIL ERROR - ".  $e->getMessage());
        }

        $request->session()->flash('success', 'Successfully Refunded');

        return redirect()->to('/flight-tickets/cancellations');
    }

     public function createSeatLive(Request $request)
    {
        $this->validate($request, [
            'book_ticket_id' => 'required|integer',
            'seat_live' => 'required'
        ]);

        $data = BookTicket::find($request->book_ticket_id);
        $cancellationCharge = RefundService::getAutoCancellationCharge($request->book_ticket_id);

        return view('flight-tickets.refunds.seatLive', compact('data','cancellationCharge'));
    }

    public function RefundCancellationRequest(Request $request)
{
    $data = $request->all();

    $user = auth()->user();
    $agent = User::where('email', $user->email)->first();

    if (!$agent) {
        return response()->json([
            "success" => false,
            "message" => "No agent found for this user email ({$user->email})"
        ], 404);
    }

    $data['user_id'] = $user->id;
    $data['agent_id'] = $agent->id;
    $data['status'] = 1;

    // Use refund[] checkboxes as passenger_ids
    $data['passenger_ids'] = $request->input('refund', []);

        $resp = CancellationRequest::create([
        'book_id'       => $request->ticket_id,
        'user_id'       => $user->id,
        'agent_id'      => $agent->id,
        'status'        => 1,
        'passenger_ids' => $request->input('refund', []),  
        'agent_remarks' => $request->remarks,            
    ]);


    return response()->json([
        "success" => true,
        "message" => 'Successfully created cancellation request',
    ]);
}


}
