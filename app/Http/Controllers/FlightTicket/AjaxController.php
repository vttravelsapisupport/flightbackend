<?php

namespace App\Http\Controllers\FlightTicket;

use App\User;
use Carbon\Carbon;
use App\PurchaseEntry;
use App\Models\SalesNote;
use App\Models\AgentMarkup;
use Illuminate\Http\Request;
use App\Models\FlightTicket\Agent;
use App\Models\FlightTicket\Owner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Mail\SaleTicketNotification;
use App\Models\FlightTicket\Airline;
use App\Models\FlightTicket\Airport;
use App\Models\FlightTicket\Credits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Models\FlightTicket\BookTicket;
use App\Models\FlightTicket\LivePNRStatus;
use App\Models\FlightTicket\BookTicketSummary;
use App\Models\FlightTicketMarkupGlobalConfig;
use App\Models\FlightTicket\SaleTicketIntimation;
use App\Models\FlightTicket\DistributorAgentAlignment;
use App\Models\FlightTicket\Accounts\AgentDebitorRemark;
use App\Models\FlightTicket\Accounts\SupplierBankDetails;
use App\Models\FlightTicket\SpiceJetPNRReconciliationDetail;
use App\Models\FlightTicket\SpiceJetPNRReconciliationSummary;

class AjaxController extends Controller
{
    public function updateMarkupGlobalConfig(Request $request){


        $resp =  FlightTicketMarkupGlobalConfig::first();

        if($resp){
            // update
            $resp ->update([
                'markup_price' => $request->markup_price
            ]);
        }else{
            FlightTicketMarkupGlobalConfig::create([
                'markup_price' => $request->markup_price
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => 'Successfully Updated',
            'data' => $resp
        ]);
    }
    public function setupAgentMarkupConfig(Request $request){

        $data = [
            'agent_id' => $request->agent_id,
            'markup_price' => $request->markup_price
        ];

        $resp =  AgentMarkup::first();

        if($resp){
            // update
            Cache::forget(
                'agent_markup_'
                .$resp->agent_id
                );

                Cache::put(
                'agent_markup_'
                .$resp->agent_id, $request->markup_price);
            $resp ->update([
                'markup_price' => $request->markup_price
            ]);
        }else{
            Cache::put(
                'agent_markup_'
                .$request->agent_id, $request->markup_price);
            AgentMarkup::create([
                'agent_id' => $request->agent_id,
                'markup_price' => $request->markup_price
            ]);
        }

        $resp = AgentMarkup::updateOrCreate($data);

        return response()->json([
            'success' => true,
            'message' => 'Successfully saved',
            'data' => $resp
        ]);



    }

    public function getAgentRemarks(Request $request){
        $limit = 5;
        if($request->agent_id != '' && $request->has('agent_id') ){
            $agent_id = $request->agent_id;
            $data = AgentDebitorRemark::with('user')->where([
                'agent_id' => $agent_id
            ])->orderBy('id', 'DESC')->first();

            return view('components.agentRemark',compact('data','agent_id'));

        }

    }
    public function getAgentCreditLimitTransaction(Request $request){
        $limit = 5;
        if($request->agent_id != '' && $request->has('agent_id') ){
            $agent_id = $request->agent_id;
            $d = DB::select("select * from `audits`
                                    where `auditable_id` = ".$agent_id."
                                    and `auditable_type` in ('App\\\\Models\\\\FlightTicket\\\\Agent')
                                    AND JSON_CONTAINS_PATH(`old_values`, 'one', '$.credit_limit')
                                    order by `id` desc LIMIT 1");
            if($d){
                $user = User::find($d[0]->user_id);
                $data= [
                    'created_at' => Carbon::parse($d[0]->created_at),
                    'old_values' => ($d[0]->old_values) ? json_decode($d[0]->old_values) : '',
                    'new_values' => ($d[0]->new_values) ? json_decode($d[0]->new_values) : '',
                    'user' => ($d[0]->user_id) ? $user->first_name . ' '.$user->last_name : '',
                ];
            }else{
                $data =false;
            }



            return view('components.agentCreditLimitTransaction',compact('data'));

        }
    }

    public function getAgentBookingTransaction(Request $request){
            $limit = 5;
            if($request->has('limit')){
                $limit = $request->limit;
            }
            $agent_id= $request->agent_id;
            if($agent_id){
                $data =BookTicket::where('agent_id',$request->agent_id)
                                ->orderBy('id','DESC')
                                ->first();
                $from = Carbon::now()->format('d-m-Y');
                $to = Carbon::now()->subDays(365)->format('d-m-Y');

                $html = view('components.agentBookingTransaction', compact('data','agent_id','from','to'))->render();
                return $html;
            }else{
                return "Send Agent ID";
            }
   }
   public function getAgentBookingUnflowTransaction(Request $request){
    $limit = 5;
    if($request->has('limit')){
        $limit = $request->limit;
    }
    $agent_id= $request->agent_id;
    if($agent_id){
        $data = BookTicket::where('agent_id',$request->agent_id)
                    ->whereDate('travel_date','>',Carbon::now())
                    ->orderBy('travel_date','ASC')->first();
        $travel_date_from = Carbon::now()->format('d-m-Y');
        $travel_date_to = Carbon::now()->addDays(365)->format('d-m-Y');
        $html = view('components.agentBookingUnflowTransaction', compact('data','agent_id','travel_date_from','travel_date_to'))->render();
        return $html;
    }else{
        return "Send Agent ID";
    }
}


   public function getAgentCreditTransaction(Request $request){
    $limit = 5;
    if($request->has('limit')){
        $limit = $request->limit;
    }
    $agent_id= $request->agent_id;
    if($agent_id){
        $data = Credits::whereIn('type', [1, 5,7])
                    ->where('agent_id',$request->agent_id)
                    ->orderBy('id','DESC')
                    ->first();

        $html = view('components.agentCreditTransaction', compact('data','agent_id'))->render();
        return $html;
    }else{
        return "Send Agent ID";
    }
}
    public function searchSupplierBankDetails(Request $request){
        $supplier_id = $request->id;

        $supplierBankDetails = SupplierBankDetails::where('supplier_id',$supplier_id)->get();
        $results =[];

        foreach($supplierBankDetails as $key => $bank_detail)
            $results[] = [
                'id' => $bank_detail->id,
                'text' => $bank_detail->account_holder_name . ' ' . $bank_detail->bank_name . ' ' . $bank_detail->ifsc_code
            ];

        return response()->json($results);

    }
    public function getBookTicketDetails(Request $request)
    {
        $id = $request->purchase_id;
        $purchase_details = \App\PurchaseEntry::findOrFail($id);
        return response()->json($purchase_details);
    }

    public function getAgentDetails(Request $request)
    {
        $id = $request->agent_id;
        $agent = Agent::select('phone', 'email')->findOrFail($id);
        return response()->json($agent);
    }

    public function updateInfantStatusUpdate(Request  $request){

        $resp = BookTicketSummary::where('type',3) // infants
        ->where('book_ticket_id',$request->id)
            ->update([
                'status' => 2
            ]);


        return response()->json([
            'success' => true,
            'message' => 'Successfully Updated'
        ]);
    }

    public function submitPurchaseEntryIntimation(Request $request){
        $selected_bill = $request->selected_bill;
        foreach($selected_bill as $key => $bill_id) {
            $sale = BookTicket::find($bill_id);
            $data =  $sale;
            $agent = Agent::find($data->agent_id);
            $subject = $request->subject;
            $subject = str_replace("BILL_NO",$sale->bill_no,$subject);
            $subject =  str_replace("PNR_NO",$sale->purchase_entry->pnr,$subject);

            $content = $request->content;

            $initimation_data = [
                'purchase_entry_id' => $data->purchase_entry_id,
                'book_ticket_id' => $data->id,
                'subject' => $subject,
                'content' => $content,
                'user_id' => Auth::id()
            ];
            SaleTicketIntimation::create($initimation_data);
            // $resp = Mail::to('deepankar.mondal@kreteq.com')
            //         ->send(new SaleTicketNotification($data, $subject, $content));

            Mail::to($agent->email)
                ->cc(['support@vishaltravels.in'])
                ->send(new SaleTicketNotification($data, $subject, $content));
        }
        return response()->json([
            'success' => true,
            'message' => 'SUCCESS'
        ]);

    }



    public function getSalesOfPurchaseEntry($purchasentry_id){

        $sales = BookTicket::with('agent')->where('purchase_entry_id',$purchasentry_id)->get();

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'data' => $sales
        ]);
    }



    public function getPurchaseEntryDetails($purchasentry_id){

        $purchaseEntryDetails = PurchaseEntry::join('owners', 'owners.id', '=', 'purchase_entries.owner_id')->where('purchase_entries.id',$purchasentry_id)->get();

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'data' => $purchaseEntryDetails
        ]);
    }



    public function getAgents(Request $request)
    {
        $q = Agent::with(['nearestAirportDetails', 'getLatestBooking', 'getAgentDebitorRemark', 'account_manager']);

        if ($request->has('agent') && $request->agent != '') {
            $q->where('id', $request->agent);
        }
        if ($request->has('airport') && $request->airport != '') {
            $q->where('nearest_airport', $request->airport);
        }
        if ($request->has('exclude_zero') && $request->exclude_zero != false) {
            $q->where('opening_balance', '!=', 0);
        }

        if ($request->has('type')
            &&  $request->has('type_amount')
            && $request->type != ''
            && $request->type_amount != '') {

            $type = ($request->type == 'greater') ? ">" : "<";
            $q->where('opening_balance', $type, $request->type_amount);
            $q->orderBy('opening_balance','ASC');
        }
        if ($request->has('airport') && $request->airport != '') {
            $data = $q->where('status', 1)->simplePaginate(50);

            return response()->json([
                'success' => true,
                'message' => 'SUCCESS',
                'data' => $data
            ]);
        } else {
            $data = $q->where('status', 1)->paginate(50);

            return response()->json([
                'success' => true,
                'message' => 'SUCCESS',
                'data' => $data
            ]);
        }
    }



    public function getPassengerDetailsWithPurchaseId(Request  $request)
    {
        $id               = $request->id;
        $purchase_entry   = PurchaseEntry::find($id);
        $booking_summary  = BookTicket::orderBy('id','DESC')->where('purchase_entry_id', $id);
        $booking_summary1 = LivePNRStatus::where('purchase_id', $id)->orderBy('id','DESC')->first();
        $airlines         = Airline::pluck('name', 'code')->toarray();

        if ($booking_summary->count() > 0)
        {
            $bookSummaries = BookTicket::with('passenger_details')
                ->where('purchase_entry_id', $id)
                ->get();
            $psg_details  = [];
            foreach ($bookSummaries as $key => $val) {
                // return $val;
                $agent = Agent::find($val->agent_id);
                $bill_no = $val->bill_no;
                $pax_price = $val->pax_price;
                $child_charge = $val->child_charge;
                $infant_price = $val->infant_charge;
                $booking_date = $val->created_at->format('d-m-Y H:i:s');

                foreach ($val->passenger_details as $val1)
                {
                    $first_name = strtolower($val1->first_name);
                    $pax_airlines = '';
                    $book_ticket_detail_airline = [
                        'destination' => 'NA',
                        'travel_date' => 'NA',
                        'flight_no' => 'NA',
                        'departure_time' => 'NA',
                        'arrival_time' =>'NA',
                        'qty' => 'NA',
                        'current_flight_status' => 'NA',
                        'pnr_flight_status' => 'NA',
                    ];

                    if($booking_summary1){
                        $airline_passengers = SpiceJetPNRReconciliationDetail::where('spice_jet_p_n_r_reconciliation_summaries_id', $booking_summary1->id)
                            ->whereRaw('lower(passenger_name) like (?)',["%{$first_name}%"])
                            ->first();

                        if($airline_passengers){
                            $pax_airlines =   $airline_passengers->passenger_name.' | '. $airline_passengers->gender.' | '. $airline_passengers->pax_type;
                        }
                        $book_ticket_detail_airline = [
                            'destination' => $booking_summary1->source .' - ' .$booking_summary1->destination,
                            'travel_date' => Carbon::parse($booking_summary1->travel_date)->format('d-m-Y'),
                            'flight_no' => $booking_summary1->flight_no,
                            'departure_time' => $booking_summary1->dep_time,
                            'arrival_time' =>$booking_summary1->arrival_time,
                            'qty' => $booking_summary1->total_pax_count,
                            'current_flight_status' => $booking_summary1->current_flight_status,
                            'pnr_flight_status' => $booking_summary1->current_flight_status,
                        ];
                    }
                    if($val->comments) {
                        $comments = $val->comments->notes . '<br> '.$val->comments->created_at->format('d-m-Y h:i:s') . '<br> '.$val->comments->user->first_name;
                    }else{
                        $comments = '';
                    }
                    if($val1->type == 3){
                        $val1->pax_portal = $val1->title .' '.$val1->first_name . ' '.$val1->last_name;
                        $val1->pax_airlines = $pax_airlines;;
                        $val1->agent = $agent->company_name;
                        $val1->agent_phone_number = $agent->phone;
                        $val1->bill_no = $bill_no;
                        $val1->pax_price = $infant_price;
                        $val1->booking_date = $booking_date;
                        $val1->comments = $comments;
                        $val1->intimation= ($val->intimations) ? 'Sent by '. $val->intimations->user->first_name  .'<br> '. $val->intimations->created_at->format('d-m-Y H:i'): '';
                        $val1->agent_remarks= ($val->intimations &&  $val->intimations->AgentIntimationRemarksOne) ? $val->intimations->AgentIntimationRemarksOne->remark : '';
                        $val1->internal_remarks= ($val->intimations && $val->intimations->InternalIntimationRemarksOne) ? $val->intimations->InternalIntimationRemarksOne->remark : '';
                        array_push($psg_details, $val1);

                    }elseif($val1->type == 2){
                        $val1->pax_portal = $val1->title .' '.$val1->first_name . ' '.$val1->last_name;
                        $val1->pax_airlines = $pax_airlines;
                        $val1->agent = $agent->company_name;
                        $val1->agent_phone_number = $agent->phone;
                        $val1->bill_no = $bill_no;
                        $val1->pax_price = $child_charge;
                        $val1->comments = $comments;
                        $val1->intimation= ($val->intimations) ? 'Sent by '.$val->intimations->user->first_name  .'<br> '. $val->intimations->created_at->format('d-m-Y H:i'): '';
                        $val1->agent_remarks= ($val->intimations &&  $val->intimations->AgentIntimationRemarksOne) ? $val->intimations->AgentIntimationRemarksOne->remark : '';
                        $val1->internal_remarks= ($val->intimations && $val->intimations->InternalIntimationRemarksOne) ? $val->intimations->InternalIntimationRemarksOne->remark : '';
                        $val1->booking_date = $booking_date;
                        array_push($psg_details, $val1);
                    }else{
                        $val1->pax_portal = $val1->title .' '.$val1->first_name . ' '.$val1->last_name;
                        $val1->pax_airlines = $pax_airlines;
                        $val1->agent = $agent->company_name;
                        $val1->agent_phone_number = $agent->phone;
                        $val1->bill_no = $bill_no;
                        $val1->pax_price = $pax_price;
                        $val1->comments = $comments;
                        $val1->intimation= ($val->intimations) ? 'Sent by '.$val->intimations->user->first_name  .'<br> '. $val->intimations->created_at->format('d-m-Y H:i'): '';
                        $val1->agent_remarks= ($val->intimations &&  $val->intimations->AgentIntimationRemarksOne) ? $val->intimations->AgentIntimationRemarksOne->remark : '';
                        $val1->internal_remarks= ($val->intimations && $val->intimations->InternalIntimationRemarksOne) ? $val->intimations->InternalIntimationRemarksOne->remark : '';

                        $val1->booking_date = $booking_date;
                        array_push($psg_details, $val1);
                    }

                }
            }

            if($purchase_entry->trip_type == 2) {
                $segments = json_decode($purchase_entry->segments);
                $pnr = explode(',', $bookSummaries[0]->pnr);
                $book_ticket_details = [];
                foreach($segments as $key => $value) {
                    $temp = [
                        'airline' => $airlines[$value->legs[0]->airline_code],
                        'destination' => $bookSummaries[0]->destination,
                        'pnr' => $pnr[$key],
                        'available' => $bookSummaries[0]->purchase_entry->available,
                        'sold' => $bookSummaries[0]->purchase_entry->sold,
                        'qty' => $bookSummaries[0]->purchase_entry->quantity,
                        'travel_date' => date('d-m-Y', strtotime($value->legs[0]->departure_date)),
                        'flight_no' => $value->legs[0]->airline_code. ' ' .$value->legs[0]->flight_number,
                        'departure_time' => $value->legs[0]->departure_time,
                        'arrival_time' => $value->legs[0]->arrival_time
                    ];
                    if($key == 0) {
                        $temp['trip_type'] = 'Onward';
                    }else{
                        $temp['trip_type'] = 'Return';
                    }
                    array_push($book_ticket_details, $temp);
                }
            }else{
                $book_ticket_details[] = [
                    'airline' => $bookSummaries[0]->airline,
                    'destination' => $bookSummaries[0]->destination,
                    'pnr' => $bookSummaries[0]->pnr,
                    'available' => $bookSummaries[0]->purchase_entry->available,
                    'sold' => $bookSummaries[0]->purchase_entry->sold,
                    'qty' => $bookSummaries[0]->purchase_entry->quantity,
                    'travel_date' => $bookSummaries[0]->purchase_entry->travel_date->format('d-m-Y'),
                    'flight_no' => $bookSummaries[0]->purchase_entry->flight_no,
                    'departure_time' => $bookSummaries[0]->purchase_entry->departure_time,
                    'arrival_time' => $bookSummaries[0]->purchase_entry->arrival_time,
                    'trip_type' => 'Onward'
                ];
            }



            $data = [
                'success' => true,
                'message' => 'Successfully Retrieved Booking Details',
                'data' => $psg_details,
                'book_detail' => $book_ticket_details,
                'book_ticket_detail_airline' => $book_ticket_detail_airline
            ];
        } else {
            $data = $booking_summary->first();

            $book_ticket_details[] = [
                'airline' => $purchase_entry->airline->name,
                'destination' => $purchase_entry->destination->name,
                'pnr' => $purchase_entry->pnr,
                'available' => $purchase_entry->available,
                'sold' => $purchase_entry->sold,
                'qty' => $purchase_entry->quantity,
                'travel_date' => $purchase_entry->travel_date->format('d-m-Y'),
                'flight_no' => $purchase_entry->flight_no,
                'departure_time' => $purchase_entry->departure_time,
                'arrival_time' => $purchase_entry->arrival_time,
                'trip_type' => 'Onward'
            ];

            $book_ticket_detail_airline = [
                'destination' => 'NA',
                'travel_date' => 'NA',
                'flight_no' => 'NA',
                'departure_time' => 'NA',
                'arrival_time' =>'NA',
                'qty' => 'NA',
                'current_flight_status' => 'NA',
                'pnr_flight_status' => 'NA',
            ];
            $data = [
                'success' => true,
                'data' => [],
                'book_detail' => $book_ticket_details,
                'message' => 'No Bookings !',
                'book_ticket_detail_airline' => $book_ticket_detail_airline,
            ];
        }

        if($booking_summary1 && $booking_summary1->count() > 0 )
        {
            $data['book_ticket_details1'] = [
                'airline' => $purchase_entry->airline,
                'pnr' => $purchase_entry->pnr,
                'airline' => $purchase_entry->airline->name,
                'source' => $booking_summary1->source,
                'destination' => $booking_summary1->destination,
                'total_pax_count' => $booking_summary1->total_pax_count,
                'travel_date' => $booking_summary1->travel_date->format('d-m-Y'),
                'flight_no' => $booking_summary1->flight_no,
                'departure_time' => $booking_summary1->dep_time,
                'pnr_status' => $booking_summary1->pnr_status,
                'flight_status' => $booking_summary1->current_flight_status,
                'arrival_time' => $booking_summary1->arrival_time,
                'passenger_details' => $booking_summary1->passengers
            ];
        }
        return response()->json($data);
    }




    public function getdebitorRemark(Request $request)
    {
        $agent_id = $request->agent_id;

        $resp = AgentDebitorRemark::with('user')->where([
            'agent_id' => $agent_id
        ])->orderBy('id', 'DESC')->simplePaginate(50);

        return response()->json(
            [
                "success" =>  true,
                "message" => 'Success',
                'data' => $resp
            ]
        );
    }




    public function debitorRemarkSubmit(Request $request)
    {
        $agent_id = $request->agent_id;
        $remarks = $request->remarks;

        $user_id = Auth::id();


        $resp = AgentDebitorRemark::create([
            'agent_id' => $agent_id,
            'remarks' => $remarks,
            'owner_id' => $user_id
        ]);

        response()->Session()->flash('success','Successfully Saved');
        return redirect(route('debitor-remarks',['agent_id'=> $request->agent_id]));
    }




    public function getAgentBySearch(Request $request)
    {
        ini_set('memory_limit', '256M');
        $agent = $request->agent;
        $q =  Agent::orderBy('id', 'DESC');
        if ($request->has('agent') && $request->agent != '') {
            $q->orWhere('phone', 'like', '%' . $agent . '%')
                ->orWhere('code', 'like', '%' . $agent . '%');
        }

        $agents = $q->get();

        return response()->json([
            "success" => true,
            "message" => 'SUCCESS',
            "data" => $agents
        ]);
    }




    public function getAirportSearch(Request $request)
    {
        $agent = $request->agent;
        $q =  Airport::where('status', 1)->orderBy('id', 'DESC');
        $agents = $q->get();

        return response()->json([
            "success" => true,
            "message" => 'SUCCESS',
            "data" => $agents
        ]);
    }




    public function searchAgents(Request $request){
        $results = [];

        $defaulter= $request->defaulter;

        $query  = $request->q;

        if($query){
            $q  = Agent::where(function ($q) use ($query) {
                return $q->where('code','like','%'.$query.'%')
                ->orWhere('phone','like','%'.$query.'%')
                ->orWhere('company_name','like','%'.$query.'%');
            });

            if($request->has('defaulter')){
                $q->where(function ($query){
                    $query->whereNull('account_type_id')
                    ->orWhere('account_type_id', '!=', '5');
                });
            }

            $agents = $q->limit(5)->get();

            foreach($agents as $key => $agent){
                $value = DistributorAgentAlignment::where('agent_id',$agent->id)->first();
                $distributor = '';
                if(isset($value->distributor)) {
                    $distributor.= ' DB - '.$value->distributor->company_name;
                }
                if($agent->status == 0) {
                    $html_data= '<div class="bg-red">'.$agent->code .' '. $agent->company_name . ' ' . $agent->phone . ' BL '. $agent->opening_balance. ' CB ' . $agent->credit_balance . $distributor . ' CL '.$agent->credit_limit.'</div>';
                }elseif($agent->account_type_id == 1){
                    $html_data= '<div class="bg-primary">'.$agent->code .' '. $agent->company_name . ' ' . $agent->phone . ' BL '. $agent->opening_balance. ' CB ' . $agent->credit_balance . $distributor . ' CL '.$agent->credit_limit. '</div>';
                }elseif($agent->account_type_id == 2){
                    $html_data= '<div class="bg-secondary">'.$agent->code .' '. $agent->company_name . ' ' . $agent->phone . ' BL '. $agent->opening_balance. ' CB ' . $agent->credit_balance . $distributor . ' CL '.$agent->credit_limit. '</div>';
                }
                elseif($agent->account_type_id == 3){
                    $html_data= '<div class="bg-warning">'.$agent->code .' '. $agent->company_name . ' ' . $agent->phone . ' BL '. $agent->opening_balance. ' CB ' . $agent->credit_balance . $distributor . ' CL '.$agent->credit_limit. '</div>';

                }elseif($agent->account_type_id == 4){
                    $html_data= '<div class="bg-dark">'.$agent->code .' '. $agent->company_name . ' ' . $agent->phone . ' BL '. $agent->opening_balance. ' CB ' . $agent->credit_balance . $distributor . ' CL '.$agent->credit_limit. '</div>';
                }
                else {
                    $html_data= '<div class="">'.$agent->code .' '. $agent->company_name . ' ' . $agent->phone . ' BL '. $agent->opening_balance. ' CB ' . $agent->credit_balance . $distributor . ' CL '.$agent->credit_limit. '</div>';
                }
                $results[] = [
                    'id' => $agent->id,
                    'text' => $agent->code . ' ' . $agent->company_name . ' ' . $agent->phone . ' BL ' . $agent->opening_balance . ' CB ' . $agent->credit_balance . $distributor . ' CL ' . $agent->credit_limit,
                    'html' => $html_data,
                    'email' => $agent->email,
                    'phone' => $agent->phone,
                ];
            }

        }
        return response()->json($results);

    }




    public function searchSupplier(Request $request): \Illuminate\Http\JsonResponse
    {
        $results = [];
        $query  = str_replace('SID-',"",$request->q);

        if($query)
        {
            $suppliers = Owner::where('name','like','%'.$query.'%')
                ->orWhere('mobile','like','%'.$query.'%')
                ->orWhere('id','like','%'.$query.'%')
                ->limit(5)
                ->get();

            foreach($suppliers as $key => $supplier)
            {
                $results[] = [
                    'id' => $supplier->id,
                    'text' => 'SID-' . $supplier->id . ' ' . $supplier->name . ' ' . $supplier->mobile . ' BL ' . $supplier->opening_balance,
                ];
            }

        }
        return response()->json($results);
    }








    public function updateIntimationReportStatus(Request $request){

        $resp = SaleTicketIntimation::where('id',$request->id)->update(['status' => 1]);

        Log::info($resp);
        if($resp) {
            return response()->json([
                'success' => true,
                'message' => 'Success',
            ],200);
        }
    }




    public function updatePaxName(Request $request){

        $book_details_id   = $request->get('book_details_id');
        $first_name        = $request->get('first_name');
        $title        = $request->get('title');
        $last_name         = $request->get('last_name');
        $dob                = $request->get('dob');

        BookTicketSummary::where('id', $book_details_id)->update([
            'title' => $title,
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'dob' => $dob
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Success',
        ],200);
    }



    public function lastChangedPriceDetails(Request $request) {
        $flight_id = $request->get('flight_id');
        $query = "SELECT
                AD.old_values,
                AD.new_values,
                AD.created_at,
                U.first_name,
                U.last_name
                FROM audits AD JOIN users U ON AD.user_id = U.id
                WHERE AD.auditable_id = $flight_id
                AND AD.new_values like '%sell_price%'
                ORDER BY AD.id DESC LIMIT  1";
        $res = DB::select(DB::raw($query));

        if(!empty($res)) {
            $old = json_decode($res[0]->old_values);
            $new = json_decode($res[0]->new_values);
            $old_price  = isset($old->sell_price) ? $old->sell_price : null;
            $new_price  = isset($new->sell_price) ? $new->sell_price : null;
            $updated_by = $res[0]->first_name.' '.$res[0]->last_name;
            $updated_at = $res[0]->created_at;

            $purchase  = PurchaseEntry::find($flight_id);

            $query = "SELECT
                U.first_name,
                U.last_name
                FROM flight_inventory_summary_sector_managers FISM JOIN users U ON FISM.manager_id = U.id
                WHERE FISM.sector_id = $purchase->destination_id ";
            $res = DB::select(DB::raw($query));
            if(!empty($res)) {
                $manager = $res[0]->first_name.' '.$res[0]->last_name;
            }else{
                $manager = '';
            }

            $html = '<div>';
            $html .= "<span class='head'>Old Price : </span><span>".$old_price."</span><br/>";
            $html .= "<span class='head'>Latest Price : </span><span>".$new_price."</span><br/>";
            $html .= "<span class='head'>Update By : </span><span>".$updated_by."</span><br/>";
            $html .= "<span class='head'>Update On : </span><span>".date('d-m-Y h:i A', strtotime($updated_at))."</span><br/>";
            $html .= "<span class='head'>Revenue Manager   : </span><span>".$manager."</span><br/>";
            $html .= '</div>';

            return $html;
        }else{
            return 'No Price Changed';
        }
    }
    public function lastChangedNameListDetails(Request $request){
        $flight_id = $request->get('flight_id');
        $query = "SELECT
                AD.old_values,
                AD.new_values,
                AD.created_at,
                U.first_name,
                U.last_name
                FROM audits AD JOIN users U ON AD.user_id = U.id
                WHERE AD.auditable_id = $flight_id
                AND AD.old_values like '%namelist_status%'
                ORDER BY AD.id DESC LIMIT  1";
        $res = DB::select(DB::raw($query));

        if(!empty($res)) {
            $namelist_name = [
                0 => 'Not Send',
                1 => 'Partially send',
                2 => 'Fully send',
                3 => 'Checked',
                4 => 'Pending',
            ];
            $old_namelist_status  = json_decode($res[0]->old_values)->namelist_status;
            $new_namelist_status  = json_decode($res[0]->new_values)->namelist_status;


            $updated_by = $res[0]->first_name.' '.$res[0]->last_name;
            $updated_at = $res[0]->created_at;

            $purchase  = PurchaseEntry::find($flight_id);

            $query = "SELECT
                U.first_name,
                U.last_name
                FROM name_list_manager_alignments NLMA
                    JOIN users U ON NLMA.user_id = U.id
                WHERE NLMA.sector_id = $purchase->destination_id  AND NLMA.airline_id = $purchase->airline_id";
            $res = DB::select(DB::raw($query));

            if(!empty($res)) {
                $manager = $res[0]->first_name.' '.$res[0]->last_name;
            }else{
                $manager = '';
            }

            $html = '<div>';
            $html .= "<span class='head'>Old NameList status : </span><span>".$namelist_name[$old_namelist_status]."</span><br/>";
            $html .= "<span class='head'>New NameList status : </span><span>".$namelist_name[$new_namelist_status]."</span><br/>";
            $html .= "<span class='head'>Update By : </span><span>".$updated_by."</span><br/>";
            $html .= "<span class='head'>Update On : </span><span>".date('d-m-Y h:i A', strtotime($updated_at))."</span><br/>";
            $html .= "<span class='head'>NameList Manager   : </span><span>".$manager."</span><br/>";
            $html .= '</div>';

            return $html;
        }else{
            return 'No Status Changed';
        }
    }



    public function getAirlineDetails(Request $request) {
        $airline = Airline::find($request->id);
        return response()->json([
            'success' => true,
            'data' => $airline,
        ],200);
    }

    public function searchAirport(Request $request){
        $query = $request->q;
        $airports = Airport::where('code','like','%'.$query.'%')
                        ->select('code','name','cityName')
                        ->groupBy('code')
                        ->limit(5)
                        ->get();
        $results = [];
        foreach($airports as $key => $airport){
            $results[] = [
                'id' => $airport->code,
                'text' => $airport->cityName . ' ' . $airport->code
            ];
        }
        
        return response()->json($results,200);
    }

}


