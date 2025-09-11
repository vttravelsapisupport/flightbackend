<?php

namespace App\Http\Controllers\FlightTicket\Reports;

use App\Http\Controllers\Controller;
use App\Models\FlightTicket\Agent;
use App\Models\FlightTicket\Airline;
use App\Models\FlightTicket\Destination;
use App\Models\FlightTicket\SaleTicketIntimation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleIntimationReportController extends Controller
{

    public function __construct() {
        $this->middleware('permission:sale-intimation-reports show', ['only' => ['index']]);
    }

    public function index(Request $request)
    {

        ini_set('memory_limit', '256M');
        $agents   = Agent::where('status',1)->get();


        $destinations = Destination::where('status',1)->get();
        $airlines = Airline::where('status',1)->pluck('name','id')->all();
        $agents   = Agent::where('status',1)->get();


        $q    =  SaleTicketIntimation::join('book_tickets','book_tickets.id','=','sale_ticket_intimations.book_ticket_id')
            ->join('agents','agents.id','=','book_tickets.agent_id')
            // ->leftjoin('intimation_remarks',function($q){
            //     $q->on('intimation_remarks.initimation_id','=','sale_ticket_intimations.id');
            // })
            ->orderBy('sale_ticket_intimations.id','DESC');




        if($request->has('agent_id') && $request->agent_id != ''){
            $q->where('book_tickets.agent_id',$request->agent_id);
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

        if($request->has('status') && $request->status != ''){
            if($request->status == 'pending') {
                $q->where('sale_ticket_intimations.status',0);
            }
            if($request->status == 'closed') {
                $q->where('sale_ticket_intimations.status',1);
            }
        }

        if($request->has('pnr_no') && $request->pnr_no != ''){
            $q->where('book_tickets.pnr',$request->pnr_no);
        }
        if($request->has('from') && $request->from != ''){
            $from = Carbon::parse($request->from);
            $to   = Carbon::parse($request->to)->endOfDay();
            $q->whereBetween('sale_ticket_intimations.created_at',[$from,$to]);
        }


        $sales_ticket_intimation = $q
            ->select('sale_ticket_intimations.id as sale_ticket_intimation_id','sale_ticket_intimations.*','book_tickets.id as book_ticket_id','book_tickets.bill_no','book_tickets.destination','book_tickets.pnr','book_tickets.pax_price','book_tickets.child','book_tickets.adults','book_tickets.travel_date','book_tickets.travel_time','book_tickets.airline','book_tickets.created_at as book_ticket_created_at','book_tickets.remark','agents.company_name','agents.phone',
                DB::raw('(select remark from intimation_remarks where initimation_id  =   sale_ticket_intimations.id and type = 2   order by id desc limit 1) as InternalRemark,
        (select created_at from intimation_remarks where initimation_id  =   sale_ticket_intimations.id and type = 2   order by id desc limit 1) as InternalRemarkCreatedAt,
        (select CONCAT(u.first_name," ", u.last_name) as name
        from intimation_remarks  as ir
        join users as u on u.id  = ir.user_id
        where ir.initimation_id  =   sale_ticket_intimations.id and ir.type = 2   order by ir.id desc limit 1) as InternalRemarkCreatedBy,
        (select remark from intimation_remarks where initimation_id  =   sale_ticket_intimations.id and type = 1   order by id desc limit 1) as AgentRemark,
        (select created_at from intimation_remarks where initimation_id  =   sale_ticket_intimations.id and type = 1   order by id desc limit 1) as AgentRemarkCreatedAt,
        (select CONCAT(u.first_name," ", u.last_name) as name
        from intimation_remarks  as ir
        join users as u on u.id  = ir.user_id
        where ir.initimation_id  =   sale_ticket_intimations.id and ir.type = 1   order by ir.id desc limit 1) as AgentRemarkCreatedBy,
        (SELECT GROUP_CONCAT(CONCAT(" ", title , " ", first_name, " " , last_name)) FROM book_ticket_details where book_ticket_details.book_ticket_id = sale_ticket_intimations.book_ticket_id) as paxDetails,
        (SELECT arrival_time from purchase_entries where purchase_entries.id=book_tickets.purchase_entry_id) as arrival_time'
                ))
            ->simplePaginate(50);
        return view('reports.intimation-reports.index',compact('agents','sales_ticket_intimation','destinations','airlines','agents' ));
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
