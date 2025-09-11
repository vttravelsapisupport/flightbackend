<?php

namespace App\Http\Controllers\FlightTicket;

use App\User;
use Carbon\Carbon;
use App\PurchaseEntry;
use App\Models\SalesNote;
use Illuminate\Http\Request;
use App\Services\AgentService;
use App\Services\CreditService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\SupplierService;
use App\Models\FlightTicket\Agent;
use App\Models\FlightTicket\Owner;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Mail\SaleTicketNotification;
use App\Models\FlightTicket\Airline;
use App\Models\FlightTicket\Credits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\FlightTicket\BookTicket;
use App\Models\FlightTicket\Destination;
use App\Models\FlightTicket\TicketService;
use App\Models\FlightTicket\BookTicketSummary;
use App\Models\FlightTicket\SaleTicketIntimation;
use App\Models\FlightTicket\TicketAdditionalServiceList;
use App\Models\FlightTicket\Accounts\SupplierTransaction;


class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function submitComment(Request $request){
        $book_ticket_id = $request->book_ticket_id;
        $remark         = $request->remark;
        $user_id        =  Auth::id();

        $resp = SalesNote::create([
            'book_ticket_id'=> $book_ticket_id,
            'user_id' =>  $user_id,
            'notes' => $remark
        ]);
        $request->session()->flash('success','Successfully Saved');
        return redirect()->back();
     }

     public function __construct() {
        $this->middleware('permission:sale-ticket delete', ['only' => ['destroy']]);
        $this->middleware('permission:sold_ticket show', ['only' => ['index']]);
    }

    public function index(Request $request)
    {
        
        $agent = null;
        $agents = [];
        $query = DB::table('book_tickets')
        ->leftJoin('agents', 'book_tickets.agent_id', '=', 'agents.id')
        ->leftJoin('users', 'users.email', '=', 'agents.email')
        ->select(
            'book_tickets.*',
            'agents.company_name',
            'agents.code'
        );
        
        if ($request->has('agency_id') && $request->agency_id != '') {
            $agent = Agent::find($request->agency_id);
            $query->where('agents.id', $request->agency_id);
        }
      
        if ($request->filled('bill_no')) {
            $query->where('book_tickets.bill_no', 'like', '%' . $request->bill_no . '%');
        }
    
        if ($request->filled('travel_date')) {
            $query->whereDate('book_tickets.departureDate', $request->travel_date);
        }
    
        if ($request->filled('pnr_no')) {
            $query->where('book_tickets.pnr', 'like', '%' . $request->pnr_no . '%');
        }

        
        if ($request->filled('src')) {
            $query->where('book_tickets.src', 'like', '%' . $request->src . '%');
        }
        
        if ($request->filled('dest')) {
            $query->where('book_tickets.dest', 'like', '%' . $request->dest . '%');
        }
    
        $data = $query->orderBy('book_tickets.id', 'desc')->get();
        foreach($data as $d){
            $pnr = $d->pnr;
            $res = json_decode($pnr, true);
              
            if ($res !== null) {
                $d->pnr = implode(",", $res);
            } else {
                $d->pnr = $pnr;
            }   
        }

        return view('flight-tickets.sales.index', compact('data', 'agent'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $data = DB::table('book_tickets')
             ->leftJoin('agents', 'book_tickets.agent_id', '=', 'agents.id')
            ->leftJoin('users', 'users.email', '=', 'agents.email')
            ->select('book_tickets.*', 'agents.company_name', 'agents.code')
            ->where('book_tickets.id', $id)
            ->first();

            if ($data) {
                $pnr = json_decode($data->pnr, true);
        
                if ($pnr !== null) {
                    $data->pnr = implode(",", $pnr);
                }
            }
        
        $ticketServices = TicketService::where('book_tickets_id', $id)->get();
       
        $book_ticket_details = BookTicketSummary::where('book_ticket_id', $id)->get();
        
        return view('flight-tickets.sales.show', compact('data', 'book_ticket_details', 'ticketServices'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $agent = null;
        $agents = [];
         $data = DB::table('book_tickets')
                ->leftJoin('users', 'book_tickets.agent_id', '=', 'users.id')
                ->leftJoin('agents', 'users.email', '=', 'agents.email')
                ->select('book_tickets.*', 'agents.company_name', 'agents.code', 'agents.email', 'agents.phone')
                ->where('book_tickets.id', $id)
                ->first();
        
        
        $book_details   = BookTicketSummary::where('book_ticket_id', $id)->get();
       
        $user           = User::find($data->agent_id);
        $agent          = Agent::find( $data->agent_id);

        return view('flight-tickets.sales.edit', compact('book_details', 'agent', 'data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {

       $book_ticket_summary = BookTicket::find($id);
       $total_pax = $book_ticket_summary->adults + $book_ticket_summary->child;
       $book_ticket_summary->delete();


       $book_ticket_details = BookTicketSummary::where('book_ticket_id', $id)->get();
       foreach ($book_ticket_details as $val) {
           $val->delete();
       }
       $purchase_entry       = PurchaseEntry::find($book_ticket_summary->purchase_entry_id);
       $agent_details        = Agent::find($book_ticket_summary->agent_id);
       $owner                = Owner::find($purchase_entry->owner_id);


       // Delete only ticket except the additional services
       Credits::where('ticket_id', $id)->where('type', 2)->delete();
       SupplierTransaction::where('ticket_id', $id)->where('type', 1)->delete();

       $ticket_total_price = $book_ticket_summary->adults * $book_ticket_summary->pax_price
           + $book_ticket_summary->child_charge * $book_ticket_summary->child
           + $book_ticket_summary->infant_charge * $book_ticket_summary->infants;


       // update the stock of the flight ticket
       $purchase_entry->increment('available', $total_pax);
       $purchase_entry->decrement('sold', $total_pax);
       // update the opening balance of the agent
       $agent_details->increment('opening_balance', $ticket_total_price);
       // update the opening balance of the supplier
       $owner->decrement('opening_balance', $ticket_total_price);


       // Update credit balance based on credit limit for agent
       if($agent_details->credit_limit > 0) {
           AgentService::updateCreditBalanceBasedOnCreditLimt($agent_details->id, 1, $ticket_total_price);
       }


       $request->session()->flash('success', 'Successfully Deleted');
       return redirect()->back();
    }



    public function print($id)
    {
        $data = BookTicket::where('id',$id)->firstOrFail();
        // $data = DB::table('book_tickets')
        //     ->leftJoin('users', 'book_tickets.agent_id', '=', 'users.id')
        //     ->leftJoin('agents', 'users.email', '=', 'agents.email')
        //     ->select('book_tickets.*', 'agents.company_name', 'agents.code', 'agents.phone')
        //     ->where('book_tickets.id', $id)
        //     ->first();
        $book_ticket_details = BookTicketSummary::where('book_ticket_id', $id)->get();
        if($data->purchase_entry_id > 0){
            $purchase_entry = PurchaseEntry::find($data->purchase_entry_id);
            return view('flight-tickets.sales.print3', compact('data', 'book_ticket_details', 'purchase_entry'));
        }else {
            $book_ticket_details = BookTicketSummary::where('book_ticket_id', $id)->get();
             return view('flight-tickets.sales.print3', compact('data', 'book_ticket_details'));
        }
       
    }



    public function pdf($id)
    {
        $data                =  BookTicket::find($id);
        $destinationDetail   =  Destination::where('name', $data->destination)->first();
        $book_ticket_details =  BookTicketSummary::where('book_ticket_id', $id)->get();
        $pdf                 =  PDF::setOption(['dpi' => 120, 'defaultFont' => 'roboto'])->loadView('flight-tickets.sales.pdf', compact('data', 'book_ticket_details'))->setPaper('a4');
        $filename            =  $destinationDetail->code . ' ' . $data->travel_date->format('d-M-Y') . ' ' . $data->adults . ' Pax ' . $data->agent->company_name . '.pdf';
        return $pdf->download($filename);
    }


    public function initimation($id, Request $request)
    {

        $data = BookTicket::find($id);
        $agent = Agent::find($data->agent_id);

        $subject = $request->subject;
        $content = $request->contents;

        $initimation_data = [
            'purchase_entry_id' => $data->purchase_entry_id,
            'book_ticket_id' => $data->id,
            'subject' => $subject,
            'content' => $content,
            'user_id' => Auth::id()
        ];

        SaleTicketIntimation::create($initimation_data);

        try {
            Mail::to($agent->email)
                ->cc(['support@vishaltravels.in'])
                ->send(new SaleTicketNotification($data, $subject, $content));
            $request->session()->flash('success', 'Successfully Send Email !');
        }catch (\Exception $e){
            $request->session()->flash('success', 'Unable to Send Email !');
        }

        if($request->intimation_list) {
            return redirect()->back();
        }else{
            return redirect(route('sales.index'));
        }
    }



    public function initimationshow($id, Request $request)
    {
        $data = BookTicket::find($id);
        $book_ticket_details = BookTicketSummary::where('book_ticket_id', $id)->get();
        $initimations = SaleTicketIntimation::where('book_ticket_id', $id)->get();
        $comments = SalesNote::where('book_ticket_id', $id)->get();
        return view('flight-tickets.sales.intimate', compact('id', 'data', 'book_ticket_details','initimations','comments'));
    }



    public function servicePageShow($id, Request $request)
    {
        $data = BookTicket::find($id);
        $additionalService = TicketAdditionalServiceList::where('status', 1)->pluck('name', 'id')->all();
        $book_ticket_details = BookTicketSummary::where('book_ticket_id', $id)->get();
        return view('flight-tickets.sales.services', compact('id', 'data', 'book_ticket_details', 'additionalService'));
    }



    public function servicePageSubmit($id, Request $request)
    {
        $bookTicketDetails = BookTicket::find($id);
        $agentServiceObj   = new AgentService();
        $current_balance    = $agentServiceObj->getCurrentAgentBalance($bookTicketDetails->agent_id);


        if ($current_balance <  $request->amount)
            return "Your current balance is Rs. $current_balance and service charge is Rs. $request->amount which is less";


        $data = [
            'additional_service_id' => $request->additional_service_id,
            'internal_remarks' => $request->internal_remarks,
            'external_remarks' => $request->external_remarks,
            'amount' => $request->amount,
            'book_tickets_id' => $id,
            'created_at' => Carbon::parse($request->date),
        ];

        $resp =  TicketService::create($data);
        // Generate Account Transaction
        $creditService  =  new CreditService();
        $creditService->createCreditRecord($bookTicketDetails->agent_id, 6, $request->amount, $id, $request->external_remarks, $request->date);
        // $agent = Agent::find($bookTicketDetails->agent_id);
        $agentServiceObj->updateAgentOpeningBalance($bookTicketDetails->agent_id, 6, $request->amount);


        //Ticket service entry for supplier
        $purchaseEntryDetail  = PurchaseEntry::where('id', $bookTicketDetails->purchase_entry_id)->first();
        $owner                = Owner::find($purchaseEntryDetail->owner_id);
        $opening_bal = 0;
        if($owner->opening_balance) {
            $opening_bal = $owner->opening_balance;
        }
        $new_balance = $opening_bal + $request->amount;
        $owner->opening_balance =  $new_balance;
        $owner->save();


        $suplier_transaction  = [
            'supplier_id'  => $purchaseEntryDetail->owner_id,
            'type'         => 3,
            'ticket_id'    => $bookTicketDetails->id,
            'amount'       => $request->amount,
            'balance'      => $new_balance,
            'remarks'      => $request->external_remarks,
            'owner_id'     => Auth::id(),
            'reference_no' => SupplierService::generateReferenceNo(),
        ];

        SupplierTransaction::create($suplier_transaction);

        $request->session()->flash('success', 'Successfully added service(s)');
        return redirect(route('sales.show', $id));
    }



    public function trashedItems(Request $request){
        $q = BookTicket::onlyTrashed()->orderBy('id','DESC');

        if($request->has('bill_no')){
            $q->where('bill_no',$request->bill_no);
        }

        $datas = $q->simplePaginate(50);


        return view('flight-tickets.sales.trashed',compact('datas'));

    }



    public function RestoreTrashedItems($id,Request $request){
        try {
            DB::beginTransaction();

            $bookTicketDetails           = BookTicket::withTrashed()->find($id);
            $bookTicketDetails->restore();
            $bookTicketSummaryDetails    = BookTicketSummary::withTrashed()->where('book_ticket_id',$bookTicketDetails->id)->get();

            foreach($bookTicketSummaryDetails as $key => $val){
                $val->restore();
            }
            $account_transaction_details = Credits::withTrashed()->where('ticket_id',$bookTicketDetails->id)->where('type',2)->first();
            $account_transaction_details->restore();

            $supplier_transaction_details = SupplierTransaction::withTrashed()->where('ticket_id',$bookTicketDetails->id)->where('type',1)->first();
            $supplier_transaction_details->restore();



            $purchase_entry_details      = \App\PurchaseEntry::withTrashed()->where('id',$bookTicketDetails->purchase_entry_id)->first();
            $agent_details               = Agent::where('id',$bookTicketDetails->agent_id)->first();
            $owner                       = Owner::find($purchase_entry_details->owner_id);

            $total_pax = $bookTicketDetails->adults + $bookTicketDetails->child;


            $purchase_entry_details->increment('sold',$total_pax);
            $purchase_entry_details->decrement('available',$total_pax);

            $agent_details->decrement('opening_balance', $account_transaction_details->amount);
            $owner->increment('opening_balance', $account_transaction_details->amount);


            DB::commit();
            $request->session()->flash('success','Successfully Restored');
            return redirect(url('flight-tickets/sales/'.$bookTicketDetails->id));
        } catch (\PDOException $e) {
            DB::rollBack();
            return $e;
        }
    }



    public function selfTicketSold(Request $request) {

        $destinations   = Destination::where('status', 1)->get();
        $agents         = Agent::where('status', 1)->get();
        $owners         = Owner::pluck('name', 'id')->all();
        $suppliers      = Owner::where('is_third_party', '=', 1)->get();

        $airlines = Airline::where('status', 1)->pluck('name', 'id')->all();

        $q= BookTicket::join('agents','agents.id','=','book_tickets.agent_id')
            ->join('destinations as d', 'd.id', '=', 'book_tickets.destination_id')
            ->join('purchase_entries as p','p.id','=','book_tickets.purchase_entry_id')
            ->join('users','users.id','=','book_tickets.created_by')
            ->join('owners','owners.id','=','p.owner_id')
            ->where('owners.is_third_party', 0)
            ->orderBy('book_tickets.id', 'DESC');


        if ($request->has('owner_id') && $request->owner_id != '')
            $q->where('p.owner_id', $request->owner_id);

        if ($request->has('supplier_id') && $request->supplier_id != '')
            $q->where('p.owner_id', $request->supplier_id);


        if ($request->has('destination_id') && $request->destination_id != '')
            $q->where('book_tickets.destination_id', $request->destination_id);

        if ($request->has('pnr_no') && $request->pnr_no != '') {
            $q->where('book_tickets.pnr', 'like', '%' . $request->pnr_no . '%');
        }
        if ($request->has('travel_date') && $request->travel_date != '')
            $q->whereDate('book_tickets.travel_date', Carbon::parse($request->travel_date));
        if ($request->has('airline') && $request->airline != '')
            $q->where('book_tickets.airline', $request->airline);
        if ($request->has('agent_id') && $request->agent_id != '')
            $q->where('book_tickets.agent_id', $request->agent_id);

        if ($request->has('bill_no') && $request->bill_no != '')
            $q->where('book_tickets.bill_no', $request->bill_no);



        if ($request->get('source')) {
            if($request->source == 'admin') {
                $q->whereNull('book_tickets.booking_source');
            }

            if($request->source == 'portal') {
                $q->where('book_tickets.booking_source', 'portal');
            }

            if($request->source == 'api') {
                $q->where('book_tickets.booking_source', 'api');
            }
        }


        $data = $q->select('book_tickets.*','agents.company_name','d.name as destination_name','p.flight_no','p.namelist_status','owners.name as owner_name','owners.is_third_party as owner_type' ,'p.deleted_at as p_deleted_at',DB::raw('CONCAT(users.first_name,users.last_name) as user_name'), DB::raw('(SELECT COUNT(*) FROM book_ticket_details WHERE book_ticket_id=book_tickets.id AND is_refund=2) as seat_live_count'))
            ->simplePaginate(50);


        return view('flight-tickets.sales.index-self', compact('data', 'destinations', 'airlines', 'agents','owners','suppliers'));
    }


    public function supplierTicketSold(Request $request) {

        $destinations   = Destination::where('status', 1)->get();
        $agents         = Agent::where('status', 1)->get();
        $owners         = Owner::pluck('name', 'id')->all();
        $suppliers      = Owner::where('is_third_party', '=', 1)->get();

        $airlines = Airline::where('status', 1)->pluck('name', 'id')->all();

        $q= BookTicket::join('agents','agents.id','=','book_tickets.agent_id')
            ->join('destinations as d', 'd.id', '=', 'book_tickets.destination_id')
            ->join('purchase_entries as p','p.id','=','book_tickets.purchase_entry_id')
            ->join('users','users.id','=','book_tickets.created_by')
            ->join('owners','owners.id','=','p.owner_id')
            ->where('owners.is_third_party', 1)
            ->orderBy('book_tickets.id', 'DESC');


        if ($request->has('owner_id') && $request->owner_id != '')
            $q->where('p.owner_id', $request->owner_id);

        if ($request->has('supplier_id') && $request->supplier_id != '')
            $q->where('p.owner_id', $request->supplier_id);


        if ($request->has('destination_id') && $request->destination_id != '')
            $q->where('book_tickets.destination_id', $request->destination_id);

        if ($request->has('pnr_no') && $request->pnr_no != '') {
            $q->where('book_tickets.pnr', 'like', '%' . $request->pnr_no . '%');
        }
        if ($request->has('travel_date') && $request->travel_date != '')
            $q->whereDate('book_tickets.travel_date', Carbon::parse($request->travel_date));
        if ($request->has('airline') && $request->airline != '')
            $q->where('book_tickets.airline', $request->airline);
        if ($request->has('agent_id') && $request->agent_id != '')
            $q->where('book_tickets.agent_id', $request->agent_id);

        if ($request->has('bill_no') && $request->bill_no != '')
            $q->where('book_tickets.bill_no', $request->bill_no);



        if ($request->get('source')) {
            if($request->source == 'admin') {
                $q->whereNull('book_tickets.booking_source');
            }

            if($request->source == 'portal') {
                $q->where('book_tickets.booking_source', 'portal');
            }

            if($request->source == 'api') {
                $q->where('book_tickets.booking_source', 'api');
            }
        }


        $data = $q->select('book_tickets.*','agents.company_name','d.name as destination_name','p.flight_no','p.namelist_status','owners.name as owner_name','owners.is_third_party as owner_type' ,'p.deleted_at as p_deleted_at',DB::raw('CONCAT(users.first_name,users.last_name) as user_name'), DB::raw('(SELECT COUNT(*) FROM book_ticket_details WHERE book_ticket_id=book_tickets.id AND is_refund=2) as seat_live_count'))
            ->simplePaginate(50);


        return view('flight-tickets.sales.index-supplier', compact('data', 'destinations', 'airlines', 'agents','owners','suppliers'));
    }


    public function apiTicketSold(Request $request) {

        $destinations   = Destination::where('status', 1)->get();
        $agent          = [];
        $owners         = Owner::pluck('name', 'id')->all();
        $suppliers      = Owner::where('is_third_party', '=', 2)->get();

        $airlines = Airline::where('status', 1)->pluck('name', 'id')->all();

        $q= BookTicket::join('agents','agents.id','=','book_tickets.agent_id')
            ->join('destinations as d', 'd.id', '=', 'book_tickets.destination_id')
            ->join('purchase_entries as p','p.id','=','book_tickets.purchase_entry_id')
            ->join('users','users.id','=','book_tickets.created_by')
            ->join('owners','owners.id','=','p.owner_id')
            ->where('owners.is_third_party', 2)
            ->whereDate('book_tickets.created_at','>=', '2023-04-01')
            ->orderBy('book_tickets.id', 'DESC');


        if ($request->has('owner_id') && $request->owner_id != '')
            $q->where('p.owner_id', $request->owner_id);

        if ($request->has('supplier_id') && $request->supplier_id != '')
            $q->where('p.owner_id', $request->supplier_id);


        if ($request->has('destination_id') && $request->destination_id != '')
            $q->where('book_tickets.destination_id', $request->destination_id);

        if ($request->has('pnr_no') && $request->pnr_no != '') {
            $q->where('book_tickets.pnr', 'like', '%' . $request->pnr_no . '%');
        }
        if ($request->has('travel_date') && $request->travel_date != '')
            $q->whereDate('book_tickets.travel_date', Carbon::parse($request->travel_date));
        if ($request->has('airline') && $request->airline != '')
            $q->where('book_tickets.airline', $request->airline);
        if ($request->has('agent_id') && $request->agent_id != '')
            $q->where('book_tickets.agent_id', $request->agent_id);
            $agent = Agent::find($request->agent_id);

        if ($request->has('bill_no') && $request->bill_no != '')
            $q->where('book_tickets.bill_no', $request->bill_no);

        if ($request->get('source')) {
            if($request->source == 'admin') {
                $q->whereNull('book_tickets.booking_source');
            }

            if($request->source == 'portal') {
                $q->where('book_tickets.booking_source', 'portal');
            }

            if($request->source == 'api') {
                $q->where('book_tickets.booking_source', 'api');
            }
        }


        $data = $q->select('book_tickets.*','agents.company_name','d.name as destination_name','p.flight_no','p.namelist_status','owners.name as owner_name','owners.is_third_party as owner_type' ,'p.deleted_at as p_deleted_at',DB::raw('CONCAT(users.first_name,users.last_name) as user_name'), DB::raw('(SELECT COUNT(*) FROM book_ticket_details WHERE book_ticket_id=book_tickets.id AND is_refund=2) as seat_live_count'))
            ->simplePaginate(50);


        return view('flight-tickets.sales.index-api', compact('data', 'destinations', 'airlines', 'agent','owners','suppliers'));
    }

}
