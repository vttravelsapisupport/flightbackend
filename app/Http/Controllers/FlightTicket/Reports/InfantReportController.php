<?php

namespace App\Http\Controllers\FlightTicket\Reports;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\FlightTicket\Agent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\FlightTicket\Airline;
use App\Models\FlightTicket\BookTicket;
use App\Models\FlightTicket\Destination;

class InfantReportController extends Controller
{

    public function __construct() {
        $this->middleware('permission:infant_report show', ['only' => ['index']]);
    }

    public function index(Request $request)
    {
        ini_set('memory_limit', '-1');
        $agents   = Agent::where('status',1)->get();
        $destinations = Destination::where('status',1)->get();
        $airlines = Airline::where('status',1)->pluck('name','id')->all();
        $limit = 100;
        if($request->has('limit') && $request->limit != ''){
           $limit = $request->limit;
        }
        $q    = BookTicket::join('purchase_entries','purchase_entries.id','=','book_tickets.purchase_entry_id')
                        ->leftJoin('book_ticket_details','book_ticket_details.book_ticket_id','=','book_tickets.id')
                        ->leftJoin('owners','owners.id','=','purchase_entries.owner_id')
                        ->leftJoin('agents','agents.id','=','book_tickets.agent_id')
                        ->where('book_ticket_details.type','=',3)
                        ->groupBy('book_tickets.id', 'book_ticket_details.book_ticket_id')
                        ->where('book_tickets.infants','>',0)->orderBy('book_tickets.id','DESC');

        if($request->has('agent_id') && $request->agent_id != ''){
            $q->where('agent_id',$request->agent_id);
        }
        if($request->has('destination_id') && $request->destination_id != ''){
            $q->where('book_tickets.destination_id',$request->destination_id);
        }
        if($request->has('bill_no') && $request->bill_no != ''){
            $q->where('book_tickets.bill_no',$request->bill_no);
        }
        if($request->has('travel_date') && $request->travel_date != ''){
            $travel_date = Carbon::parse($request->travel_date);

            $q->whereDate('book_tickets.travel_date',$travel_date);
        }
        if($request->has('airline') && $request->airline != ''){
            $airline_name = Airline::find($request->airline)->name;
            $q->where('book_tickets.airline',$airline_name);
        }
        if($request->has('pnr_no') && $request->pnr_no != ''){
            $q->where('book_tickets.pnr',$request->pnr_no);
        }
        if($request->has('from') && $request->from != ''){
            $from = Carbon::parse($request->from);
            $to   = Carbon::parse($request->to)->endOfDay();
            $q->whereBetween('book_tickets.created_at',[$from,$to]);

            if($from->format('Y-m-d') <= '2023-03-31' ||
                $to->format('Y-m-d') <= '2023-03-31'
            ){
                $data = collect([]);
                return view('reports.infant-reports.index',compact('agents','destinations','airlines','data'));
            }
        }


        $data = $q->select(
            'book_tickets.id','agents.company_name',
            'book_tickets.bill_no','book_tickets.destination',
            'book_tickets.pnr','owners.is_third_party',
            'owners.name as owner_name',

            'book_tickets.infants as infants','book_tickets.infant_charge',
             DB::raw('CONCAT(\'[\', GROUP_CONCAT(JSON_OBJECT(\'title\', book_ticket_details.title, \'first_name\', book_ticket_details.first_name,\'last_name\', book_ticket_details.last_name,\'travelling_with\', book_ticket_details.travelling_with,\'status\', book_ticket_details.status)), \']\') AS details_data'),
            'book_tickets.travel_date','book_tickets.travel_time',
            'book_tickets.airline','book_tickets.created_at','book_tickets.remark')
            ->simplePaginate($limit);
      
        return view('reports.infant-reports.index',compact('agents','destinations','airlines','data'));
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
    public function destroy($id)
    {
        //
    }
}
