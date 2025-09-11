<?php

namespace App\Http\Controllers\FlightTicket\Reports;

use Carbon\Carbon;
use App\Models\State;
use Illuminate\Http\Request;
use App\Exports\SalesReportExport;
use App\Models\FlightTicket\Agent;
use App\Models\FlightTicket\Owner;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\FlightTicket\Airline;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\FlightTicket\BookTicket;
use App\Models\FlightTicket\Destination;

class SalesReportController extends Controller
{

    public function __construct() {
        $this->middleware('permission:sales-report-agent-wise show', ['only' => ['AgentWise']]);
        $this->middleware('permission:sales-report-vendor-wise show', ['only' => ['VendorWise']]);
        $this->middleware('permission:sale_report show', ['only' => ['index']]);
        $this->middleware('permission:sales-reports-with-vendor show', ['only' => ['index']]);
        $this->middleware('permission:refund-reports-with-vendor show', ['only' => ['index']]);



    }

    public function index(Request  $request){

        $destinations = Destination::where('status',1)->get();
        $airlines     = Airline::where('status',1)->pluck('name','id')->all();
        $agents       = Agent::where('status',1)->get();
        $all_owners   = Owner::select('name', 'id','is_third_party')->get();
        $owners       = $all_owners->where('is_third_party','!=',1);
        $suppliers    = $all_owners->where('is_third_party', '=', 1);
        $limit        = 200;

        $q= BookTicket::join('purchase_entries','purchase_entries.id','=','book_tickets.purchase_entry_id')
                        ->join('owners','owners.id','=','purchase_entries.owner_id')
                        ->join('agents','agents.id','=','book_tickets.agent_id')
                        ->join('states','agents.state_id','=','states.id')
                        ->whereDate('book_tickets.created_at','>=','2023-04-01')
                        ->join('destinations','purchase_entries.destination_id','=','destinations.id')
                        ->orderBy('book_tickets.id','DESC');

        if($request->has('agent_id') && $request->agent_id != ''){
            $q->where('book_tickets.agent_id',$request->agent_id);
        }
        if($request->has('destination_id') && $request->destination_id != ''){
            $q->where('purchase_entries.destination_id',$request->destination_id);
        }
        if($request->has('bill_no') && $request->bill_no != ''){
            $q->where('book_tickets.bill_no',$request->bill_no);
        }

        if($request->has('travel_date_from') && $request->travel_date_from != '' && $request->has('travel_date_to') && $request->travel_date_to != ''){
            $from = Carbon::parse($request->travel_date_from);
            $to   = Carbon::parse($request->travel_date_to)->endOfDay();
            $q->whereBetween('purchase_entries.travel_date',[$from,$to]);

            if($from->format('Y-m-d') <= '2023-03-31' ||
                $to->format('Y-m-d') <= '2023-03-31'
            ){
                $data = collect([]);
                return view('reports.sales-reports.index',compact('data','destinations','airlines','agents','owners','suppliers'));
            }
        }


        if($request->has('airline') && $request->airline != ''){
            //$airline_name = Airline::find($request->airline)->name;
            $q->where('purchase_entries.airline_id',$request->airline);
        }
        if($request->has('pnr_no') && $request->pnr_no != ''){
            $q->where('purchase_entries.pnr',$request->pnr_no);
        }
        if ($request->has('owner_id') && $request->owner_id != '')
            $q->where('purchase_entries.owner_id', $request->owner_id);

        if ($request->has('supplier_id') && $request->supplier_id != '')
            $q->where('purchase_entries.owner_id', $request->supplier_id);


        if($request->has('from') && $request->from != '' && $request->has('to') && $request->to != ''){
            $from = Carbon::parse($request->from);
            $to   = Carbon::parse($request->to)->endOfDay();
            $q->whereBetween('book_tickets.created_at',[$from,$to]);

            if($from->format('Y-m-d') <= '2023-03-31' ||
                $to->format('Y-m-d') <= '2023-03-31'
            ){
                $data = collect([]);
                return view('reports.sales-reports.index',compact('data','destinations','airlines','agents','owners','suppliers'));
            }
        }

        if ($request->has('result') && $request->result != ''){
            $limit = $request->result;
        }

        $data = $q
            ->select('purchase_entries.id',
            'purchase_entries.flight_no',
            'book_tickets.id as book_ticket_id',
            'book_tickets.id as book_ticket_id',
            'book_tickets.agent_markup','book_tickets.agent_id',
            'book_tickets.bill_no','book_tickets.destination','book_tickets.pnr',
            'destinations.code as destination_code',
            'book_tickets.adults','book_tickets.child','book_tickets.infants',
            'book_tickets.pax_price','book_tickets.child_charge','book_tickets.infant_charge',
            'book_tickets.travel_date','book_tickets.travel_time','book_tickets.airline',
            'purchase_entries.owner_id','book_tickets.created_at','book_tickets.remark',
            'owners.is_third_party','owners.name','agents.company_name',
            DB::raw('(book_tickets.adults+book_tickets.child+book_tickets.infants) as pax_count'),
            DB::raw('(SELECT concat(title," ",first_name," ",last_name) as pax_name from book_ticket_details where book_ticket_id = book_tickets.id LIMIT 1 ) as pax_name'),
            'agents.code as company_code','states.name as company_state')
            ->simplePaginate($limit);

        return view('reports.sales-reports.index',compact('data','destinations','airlines','agents','owners','suppliers'));
    }

    public function vendorShow(Request $request)
    {
        if($request->has('from') && $request->has('to')){
            set_time_limit(0);

            $from = DATE("Y-m-d", strtotime($request->input('from')));
            $to = DATE("Y-m-d", strtotime($request->input('to')));

            $from1 = Carbon::parse($request->from);
            $to1 = Carbon::parse($request->to);

            if($from1->format('Y-m-d') <= '2023-03-31' ||
                $to1->format('Y-m-d') <= '2023-03-31'
            ){
                $salesReports = collect([]);
                return view('reports.sales-reports-with-vendor.index', compact('salesReports'));
            }

            $sql = "
            SELECT a.company_name, a.code, bt.bill_no, d.name as destination_name,
            bt.pnr, bt.adults, bt.child, bt.infants, bt.pax_price as adult_charge,
            bt.infant_charge, bt.child_charge, pe.cost_price,
            ((bt.adults * bt.pax_price) + (bt.child * bt.child_charge) + (bt.infants * bt.infant_charge)) AS total_price,
            bt.travel_date, bt.travel_time, bt.airline, bt.destination, pe.airline_id,
            bt.created_at AS BookingDate, o.name as vendor_name,
            (SELECT CONCAT(btd.title, ' ', btd.first_name, ' ', btd.last_name)
            FROM book_ticket_details as btd WHERE btd.book_ticket_id = bt.id LIMIT 1)
            AS pax_name
            FROM book_tickets as bt
            JOIN agents as a ON a.id = bt.agent_id
            JOIN states as s ON s.id = a.state_id
            JOIN purchase_entries as pe ON pe.id = bt.purchase_entry_id
            JOIN destinations as d ON d.id = pe.destination_id
            JOIN owners as o ON o.id = pe.owner_id
            JOIN airlines as air ON air.id = pe.airline_id
            WHERE bt.deleted_at IS NULL
            AND (bt.created_at BETWEEN '$from' AND '$to')";
            $salesReports = DB::select($sql);
        }else{
            $salesReports = false;
        }


        return view('reports.sales-reports-with-vendor.index', compact('salesReports'));
    }

    public function refundShow(Request $request){

        if($request->has('from') && $request->has('to')){
            set_time_limit(0);

        $from = DATE("Y-m-d", strtotime($request->input('from')));
        $to = DATE("Y-m-d", strtotime($request->input('to')));

            $from1 = Carbon::parse($request->from);
            $to1 = Carbon::parse($request->to);

            if($from1->format('Y-m-d') <= '2023-03-31' ||
                $to1->format('Y-m-d') <= '2023-03-31'
            ){
                $refundReports = collect([]);
                return view('reports.refund-reports-with-vendor.index', compact('refundReports'));
            }

        $sql = "
        SELECT a.company_name,a.code,d.name,bt.airline,pe.travel_date,bt.bill_no,atr.adult + atr.child  as pax,
        atr.infant,bt.pax_price as Fare,
        atr.pax_cost as Charge,
        bt.pax_price - atr.pax_cost as RefundPP,
        (bt.pax_price - atr.pax_cost) * (atr.adult + atr.child) as TotalRefund,
        atr.infant * bt.infant_charge as InfantRefund,
        atr.created_at as RefundDateAndTime,
        o.name as Vendor,
        CONCAT(u.first_name,u.last_name) as User,
        atr.remarks as Remarks
        FROM air_ticket_refunds as atr
        join agents as a on a.id = atr.agent_id
        join book_tickets as bt on bt.id = atr.book_ticket_id
        join destinations as d on d.id = bt.destination_id
        join purchase_entries as pe on pe.id = bt.purchase_entry_id
        join owners as o on o.id = pe.owner_id
        join users as u on u.id = atr.owner_id
        WHERE (atr.created_at BETWEEN '$from' AND '$to')";
        $refundReports = DB::select($sql);

        } else {
            $refundReports = false;
        }

        return view('reports.refund-reports-with-vendor.index', compact('refundReports'));

    }



    public function excel(Request  $request){
        $data = new SalesReportExport($request);
        if ($data->collection()->isEmpty()) {
             return back()->with('error', 'Cannot export an empty data sheet.');
        }
        return Excel::download($data, 'sales-reports-export.xlsx');
    }

    public function SectorWise(Request $request){
            $start_date = Carbon::now()->subDays(30);
            $end_date = Carbon::now();

            $sectors = Destination::pluck('name','id')->all();

            $q = BookTicket::orderBy('destinations.name','ASC')
            ->join('destinations','destinations.id','=','book_tickets.destination_id')
            ->groupBy('book_tickets.destination_id')
            ->select(DB::raw('sum(book_tickets.adults) as total_adult'),DB::raw('sum(book_tickets.child) as total_child'),DB::raw('sum(book_tickets.infants) as total_infants')
            ,DB::raw('sum(book_tickets.infants  * book_tickets.infant_charge) as total_infant_charge'),
            DB::raw('sum(book_tickets.child  * book_tickets.child_charge) as total_child_charge'),
            DB::raw('sum(book_tickets.pax_price  * book_tickets.adults) as total_adults_charge'),
            'destinations.name')
            ->whereBetween('book_tickets.created_at',[$start_date,$end_date]);

            if($request->start_date != '' && $request->has('start_date') && $request->end_date != '' && $request->has('end_date'))
            {
                $start_date = Carbon::parse($request->start_date);
                $end_date = Carbon::parse($request->end_date);

                if($start_date->format('Y-m-d') <= '2023-03-31' ||
                    $end_date->format('Y-m-d') <= '2023-03-31'
                ){
                    $data = collect([]);
                    return view('reports.sales-reports.sector_wise',compact('data','sectors'));
                }
            }

            if($request->sector_id != '' && $request->has('sector_id') ){
               $q->where('book_tickets.destination_id',$request->sector_id);
            }

            $data = $q->paginate(50);



            return view('reports.sales-reports.sector_wise',compact('data','sectors'));
    }
    public function AgentWise(Request $request)
    {
        $limit = 100;
        $agent = null;
        $states= State::pluck('name','id')->all();

        if($request->has('from') && $request->from && $request->has('to') && $request->to){
            $from = Carbon::parse($request->from)->startOfDay();
            $to   = Carbon::parse($request->to)->endOfDay();

            if($from->format('Y-m-d') <= '2023-03-31' ||$to->format('Y-m-d') <= '2023-03-31'){
                $agents = collect([]);
                return view('reports.sales-reports.agent_wise',compact('agents','from','to','agent','states'));
            }

        }else{
            $from = Carbon::parse(date('Y-m-d'))->startOfDay();
            $to   = Carbon::parse(date('Y-m-d'))->endOfDay();
        }
        $q  = Agent::join('states','states.id','=','agents.state_id')
                ->leftJoin('book_tickets', 'agents.id', '=', 'book_tickets.agent_id')
                ->orderBy('agents.id','ASC')
                ->whereNull('deleted_at')
                ->groupBy('agents.id')
                ->whereBetween('book_tickets.created_at',[$from,$to]);

        if($request->has('agent_id') && $request->agent_id) {
            $agent = Agent::find($request->agent_id);
            $q->where('agents.id',$request->agent_id);
        }

        if($request->has('state_id') && $request->state_id) {
            $q->where('agents.state_id',$request->state_id);
        }

        if($request->has('type') && $request->type) {
            $type = $request->type;
            if($type == 1){
                $q->where('agents.has_api',1);
            }elseif($type == 2){
                $q->where('agents.has_api',0);
            }
        }

        if ($request->has('result') && $request->result != '') {
            $limit =  $request->result;
        }

        $agents = $q
        ->select('agents.company_name','agents.code',
        'states.name as state_name',
        DB::raw('SUM(((book_tickets.pax_price * book_tickets.adults)+(book_tickets.child_charge * book_tickets.child)+(book_tickets.infant_charge * book_tickets.infants))) as total_sales'),
        DB::raw('SUM(book_tickets.adults +  book_tickets.child +  book_tickets.infants) as booking_count'),
        DB::raw('sum(book_tickets.infants) as infant_count'))
        ->simplePaginate($limit);

        return view('reports.sales-reports.agent_wise',compact('agents','from','to','agent','states'));
    }

    public function VendorWise(Request $request)
    {
        $distributor = null;

        if($request->has('from') && $request->from && $request->has('to') && $request->to){
            $from = Carbon::parse($request->from)->startOfDay();
            $to   = Carbon::parse($request->to)->endOfDay();
            if($from->format('Y-m-d') <= '2023-03-31' ||
                $to->format('Y-m-d') <= '2023-03-31'
            ){
                $vendors = collect([]);
                return view('reports.sales-reports.vendor_wise',compact('vendors','from','to','distributor'));

            }
        }else{
            $from = Carbon::parse(date('Y-m-d'))->startOfDay();
            $to   = Carbon::parse(date('Y-m-d'))->endOfDay();
        }

        $q  = Owner::orderBy('id','ASC');

        if($request->has('vendor_id') && $request->vendor_id) {
            $distributor = Owner::find($request->vendor_id);
            $q->where('id',$request->vendor_id);
        }

        if($request->has('type') && $request->type) {
            $type = $request->type;
            if($type == 1){
                $q->where('is_third_party',0);
            }elseif($type == 2){
                $q->where('is_third_party',1);
            }
            elseif($type == 3){
                $q->where('is_third_party',2);
            }
        }
        $limit = 20;

        if ($request->has('result') && $request->result != '') {
            $limit =  $request->result;
        }
        $vendors = $q->simplePaginate($limit);

        return view('reports.sales-reports.vendor_wise',compact('vendors','from','to','distributor'));
    }
}
