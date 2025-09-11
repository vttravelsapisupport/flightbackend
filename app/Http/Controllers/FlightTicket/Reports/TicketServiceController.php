<?php

namespace App\Http\Controllers\FlightTicket\Reports;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\FlightTicket\Agent;
use App\Models\FlightTicket\Owner;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\FlightTicket\BookTicket;
use App\Models\FlightTicket\TicketService;

class TicketServiceController extends Controller
{

    public function __construct() {
        $this->middleware('permission:ticket_service_report show', ['only' => ['index']]);        
    }

    public function showDeletePage(Request $request)
    {
        $agents = Agent::where('status', 1)->pluck('code', 'id')->all();

        if ($request->has('agent_id')) {
            $q = TicketService::join('book_tickets', 'ticket_services.book_tickets_id', '=', 'book_tickets.id');
            $q->where('book_tickets.agent_id', $request->agent_id);
            $q->select('book_tickets.*', 'ticket_services.id as ticket_service_id', 'ticket_services.*');
            $ticket_services = $q->get();
        } else {
            $ticket_services = [];
        }

        return view('tickets.ticket-services.delete', compact('agents', 'ticket_services'));
    }
    public function index(Request  $request)
    {
        $agent = null;

        $owner = Owner::where('is_third_party',0)->pluck('id','name')->all();
        $third_party_owner = Owner::where('is_third_party',1)->pluck('id','name')->all();

        $q    = TicketService::join('book_tickets as bt','bt.id','=','ticket_services.book_tickets_id')
                            ->join('agents as a','a.id','=','bt.agent_id')
                            ->join('purchase_entries as pe','pe.id','=','bt.purchase_entry_id')
                            ->join('owners as o','o.id','=','pe.owner_id')
                            ->join('ticket_additional_service_lists as tas','tas.id','=','ticket_services.additional_service_id')
                            ->whereDate('bt.created_at','>=','2023-04-01')
                            ->orderBy('ticket_services.created_at', 'DESC');

        if ($request->has('agent_id') && $request->agent_id != '') {
            $agent = Agent::find($request->agency_id);
            $q ->where('bt.agent_id', $request->agent_id);
        }
        if ($request->has('bill_no') && $request->bill_no != '') {
            $q ->where('bt.bill_no', $request->bill_no);
        }
        if ($request->has('pnr_no') && $request->pnr_no != '') {
            $q ->where('bt.pnr', $request->pnr_no);
        }
        if ($request->has('owner_id') && $request->owner_id != '') {
            $q ->where('pe.owner_id', $request->owner_id);
        }

        if ($request->has('third_party_id') && $request->third_party_id != '') {
            $q ->where('pe.owner_id', $request->third_party_id);
        }

        if ($request->has('from') && $request->from != '' && $request->has('to') && $request->to != '') {
            $from = Carbon::parse($request->from)->startOfDay();
            $to = Carbon::parse($request->to)->endOfDay();
            $q ->whereBetween('ticket_services.created_at', [$from,$to]);

            if($from->format('Y-m-d') <= '2023-03-31' ||
                $to->format('Y-m-d') <= '2023-03-31'
            ){
                $data = collect([]);
                return view('reports.ticket-service-reports.index', compact('data',  'agent','owner','third_party_owner'));
            }
        }
        $limit = 100;
        if($request->has('limit') && $request->limit != ''){
           $limit = $request->limit;
        }
        $data = $q->select(
            'a.company_name',
            'a.code',
            'o.name as owner_name',
            'o.is_third_party as is_third_party',
            'tas.name as additional_service_name',
            'ticket_services.id',
            'ticket_services.created_at','bt.agent_id','bt.bill_no',
            'bt.destination',
            'bt.pnr','bt.adults','bt.infants','ticket_services.additional_service_id','ticket_services.amount','ticket_services.internal_remarks','ticket_services.external_remarks')
            ->simplePaginate($limit);

        return view('reports.ticket-service-reports.index', compact('data',  'agent','owner','third_party_owner'));
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
    public function show()
    {
        $path = public_path() . '/json/ticket_import.json';

        $data = json_decode(file_get_contents($path), true);

        $total_data = [];
        foreach ($data as $key => $val) {
            $service_data = [];
            $agent_code = $val["Agent ID"];
            $agent = Agent::where('code', $agent_code)->first();
            $book_ticket = BookTicket::where('pnr', $val['pnr'])->where('agent_id', $agent->id)->first();

            $service_data['agent_id'] = $agent;
            $service_data['book_ticket'] = $book_ticket;
            $service_data['pnr'] = $val['pnr'];
            $service_data['amount'] = $val['amount'];
            $service_data['date'] = $val['Date'];
            $service_data['internal_remarks'] = $val['internal_remarks'];
            $service_data['external_remarks'] = $val['external_remarks'];
            $service_data['additional_service_id'] = $val['additional_service_id'];

            $test = (object)($service_data);
            array_push($total_data, $test);
        }

        return view('ticket_import', compact('total_data'));
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

    public function submitDeleteForm(Request $request, $id)
    {
        $resp = TicketService::find($id);
        $resp->delete();
        return response()->json([
            'success' => true,
            'message' => 'Success'
        ]);
    }    

    function deleteDuplicateInfantCharges(Request $request)
    {
        $id              = $request->id;          
        $transactionType = 6;  // Define TransactionType      

        // Get ticketServiceData using id
        $ticketServiceData = DB::table('ticket_services')->where('id', $id)->orderBy('created_at', 'desc')->orderBy('id', 'desc')->first();

        if ($ticketServiceData) {
            $ticketServiceID = $ticketServiceData->id;
            $ticketID        = $ticketServiceData->book_tickets_id;

            // Get AgentID using TicketID
            $ticketData = DB::table('book_tickets')->where('id', $ticketID)->select('id', 'agent_id')->first();                        
            $agentID    = $ticketData->agent_id;

            // Soft delete ticket_services entry
            DB::table('ticket_services')->where('id', $ticketServiceID)->update(['deleted_at' => now()]);

            // Get TransactionID and TransactionAmount using TicketID
            $transactionData = DB::table('account_transaction')->where('ticket_id', $ticketID)->where('type', $transactionType)->orderBy('created_at', 'desc')->orderBy('id', 'desc')->first();

            if ($transactionData) {
                $transactionID = $transactionData->id;
                $transactionAmount = $transactionData->amount;

                // Soft delete account transaction entry
                DB::table('account_transaction')->where('id', $transactionID)->update(['deleted_at' => now()]);

                // Add the transaction amount to agent's opening balance
                DB::table('agents')->where('id', $agentID)->update(['opening_balance' => DB::raw("opening_balance + $transactionAmount")]);
            }
        }
        return 1;
    }
}
