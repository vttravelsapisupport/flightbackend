<?php

namespace App\Http\Controllers\FlightTicket\Reports;

use App\Exports\AirTicketRefundExport;
use App\Http\Controllers\Controller;
use App\Models\FlightTicket\Agent;
use App\Models\FlightTicket\Owner;
use App\Models\FlightTicket\Airline;
use App\Models\FlightTicket\AirTicketRefunds;
use App\Models\FlightTicket\Destination;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RefundReportController extends Controller
{

    public function __construct() {
        $this->middleware('permission:refund_report show', ['only' => ['index']]);
    }

    public function index(Request $request){


        $agents =  Agent::where('status', 1)->get();
        $owners =  Owner::where('status', 1)->get();

        // $destinations =  Destination::pluck('name','id')->all();
        $destinations = Destination::where('status',1)->get();
        $airlines     = Airline::pluck('name','id')->all();

        $q  = AirTicketRefunds::join('book_tickets','book_tickets.id','=','air_ticket_refunds.book_ticket_id')
            ->join('agents','agents.id','=','air_ticket_refunds.agent_id')
            ->join('destinations','destinations.id','=','book_tickets.destination_id')
            ->join('purchase_entries','purchase_entries.id','=','book_tickets.purchase_entry_id')
            ->join('owners','owners.id','=','purchase_entries.owner_id')
            ->whereDate('book_tickets.created_at','>=','2023-04-01')
            ->join('users','users.id','=','air_ticket_refunds.owner_id');

        if($request->has('agent_id') && $request->agent_id != ''){
            $q->where('air_ticket_refunds.agent_id',$request->agent_id);
        }

        if($request->has('owner_id') && $request->owner_id != ''){
            $q->where('owners.id',$request->owner_id);
        }

        if($request->has('bill_no') && $request->bill_no != ''){
            $q->whereHas('bookTicket', function($query) use ($q,$request){
                $query->where('bill_no', '=',  $request->bill_no);
            });
        }
        //destination
        if($request->has('destination_id') && $request->destination_id != ''){
            $q->whereHas('bookTicket', function($query) use ($q,$request){
                $query->where('destination_id', '=',  $request->destination_id);
            });
        }
        //travel_date
        if($request->has('travel_date') && $request->travel_date != ''){
            $q->whereHas('bookTicket', function($query) use ($q,$request){
                $query->whereDate('travel_date',Carbon::parse($request->travel_date));
            });
        }
        //airline
        if($request->has('airline') && $request->airline != ''){
            $q->whereHas('bookTicket', function($query) use ($q,$request){
                $query->where('airline',$request->airline);
            });
        }
        // pnr
        if($request->has('pnr_no') && $request->pnr_no != ''){
            $q->whereHas('bookTicket', function($query) use ($q,$request){
                $query->where('pnr',$request->pnr_no);
            });
        }

        // created date and time range
        if($request->has('from') && $request->from != '' && $request->has('to') && $request->to != ''){
            $from = Carbon::parse($request->from);
            $to   = Carbon::parse($request->to)->endOfDay();
            $q->whereBetween('air_ticket_refunds.created_at',[$from,$to]);

            if($from->format('Y-m-d') <= '2023-03-31' ||
                $to->format('Y-m-d') <= '2023-03-31'
            ){
                $datas = collect([]);
                return view('reports.refund-reports.index',compact('datas','agents','airlines','destinations','owners'));
            }
        }

        $limit = 100;
        if($request->has('limit') && $request->limit != ''){
           $limit = $request->limit;
        }

        $datas = $q->select('owners.name as owner_name',
        'owners.is_third_party',
        'air_ticket_refunds.created_at as refund_created_at','air_ticket_refunds.total_refund','agents.company_name as agency_name','book_tickets.airline as airline_name','book_tickets.travel_date as travel_date',
            'book_tickets.bill_no', 'book_tickets.pnr', 'air_ticket_refunds.adult',
            'air_ticket_refunds.child','air_ticket_refunds.infant', 'book_tickets.pax_price','book_tickets.infant_charge','air_ticket_refunds.pax','air_ticket_refunds.pax_cost','users.first_name','air_ticket_refunds.remarks','destinations.name as destination_name'
        )->orderBy('air_ticket_refunds.id','DESC')->simplePaginate($limit);

        //return $datas;
        // $agents-distributors =  Agent::pluck('company_name','id')->all();

        return view('reports.refund-reports.index',compact('datas','agents','airlines','destinations','owners'));
    }


    public function excel(Request $request){
        $data = new AirTicketRefundExport($request);
        if ($data->collection()->isEmpty()) {
            return back()->with('error', 'Cannot export an empty data sheet.');
        }
        return Excel::download($data, 'refund-reports-export.xlsx');
    }
}
