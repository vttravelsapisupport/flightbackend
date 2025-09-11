<?php

namespace App\Http\Controllers\FlightTicket\Reports;

use App\Http\Controllers\Controller;
use App\Models\FlightTicket\Agent;
use App\Models\FlightTicket\Airline;
use App\Models\FlightTicket\BlockTicket;
use App\Models\FlightTicket\Destination;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BlockReportController extends Controller
{

    public function __construct() {
        $this->middleware('permission:block_report show', ['only' => ['index']]);
    }

    public function index(Request $request){

        $destinations = Destination::where('status', 1)->get();
        $airlines = Airline::where('status', 1)->pluck('name','id')->all();
        $agents = Agent::where('status', 1)->get();


        $q  = BlockTicket::join('agents','agents.id','=','block_tickets.agent_id')
            ->join('purchase_entries','purchase_entries.id','=','block_tickets.purchase_entry_id')
            ->join('users','users.id','=','block_tickets.created_by')
            ->whereDate('purchase_entries.travel_date','>=','2023-04-01')
            ->join('airlines','airlines.id','=','purchase_entries.airline_id')
            ->join('destinations','destinations.id','=','purchase_entries.destination_id')
            ->orderBy('block_tickets.id','DESC');


        if($request->has('destination_id') && $request->destination_id != ''){
            $q->whereHas('purchase_entry', function($query) use ($q,$request){
                $query->where('destination_id',$request->destination_id);
            });
        }


        if($request->has('airline') && $request->airline != ''){
            $q->whereHas('purchase_entry', function($query) use ($q,$request){
                $query->where('airline_id',$request->airline);
            });
        }


        if($request->has('pnr_no') && $request->pnr_no != ''){
            $q->whereHas('purchase_entry', function($query) use ($q,$request){
                $query->where('pnr',$request->pnr_no);
            });
        }


        if($request->has('travel_date_from') && $request->travel_date_from != '' && $request->has('travel_date_to') && $request->travel_date_to != ''){
            $from = Carbon::parse($request->travel_date_from);
            $to   = Carbon::parse($request->travel_date_to)->endOfDay();
            $q->whereBetween('purchase_entries.travel_date',[$from,$to]);

            if($from->format('Y-m-d') <= '2023-03-31' ||
                $to->format('Y-m-d') <= '2023-03-31'
            ){
                $data = collect([]);
                return view('reports.block-reports.index',compact('data','destinations','airlines','agents'));
            }
        }


        if($request->has('agent_id') && $request->agent_id != ''){
            $q->where('block_tickets.agent_id',$request->agent_id);

        }


        $data = $q->select('block_tickets.id as book_ticket_id',
            'block_tickets.quantity as quantity','block_tickets.remarks as remarks',
            'block_tickets.created_at as created_at','purchase_entries.pnr as pnr','purchase_entries.travel_date as travel_date','airlines.name as airline_name','agents.company_name as agency_name','destinations.name as destination_name','users.first_name as first_name','users.last_name')->simplePaginate(200);



        return view('reports.block-reports.index',compact('data','destinations','airlines','agents'));
    }
}
