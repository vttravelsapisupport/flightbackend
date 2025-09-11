<?php

namespace App\Http\Controllers\FlightTicket\Accounts;


use Carbon\Carbon;
use App\PurchaseEntry;
use App\SerialCounter;
use Illuminate\Http\Request;
use App\Models\FlightTicket\Agent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\FlightTicket\Credits;
use App\Models\FlightTicket\BookTicket;
use App\Models\FlightTicket\Accounts\FY;
use App\Models\FlightTicket\NameListStatus;
use App\Models\FlightTicket\Accounts\AgentOpeningBalanceFY;

class AgentLedgerController extends Controller
{

    public function __construct() {
        $this->middleware('permission:agent_ledger show', ['only' => ['index']]);
        $this->middleware('permission:distributor-ledger show', ['only' => ['showDistributorLedgerPage']]);
    }

    public function getAgentLedgerDeletePage(Request $request)
    {

        $agents = Agent::where('status', 1)->whereIn('type', [1,2] )->get();
        $data = [];
        $datas = [];
        $amount = 0;
        $credit_balance = 0;
        $date_from = Carbon::parse($request->start_date)->startOfDay();
        $date_end  = Carbon::parse($request->end_date)->endOfDay();

        $fys = FY::where('isActive', 1)->first();

        $q   = Credits::orderBy('created_at', 'ASC');
        $q->orWhereIn('type', [1, 2, 3, 4, 5, 6,9,10,11]);

        if ($request->has('agent_id') && $request->agent_id != '') {


            $q->where('agent_id',$request->agent_id);
            $q->whereBetween('created_at',[$date_from,$date_end]);
            $datas =  $q->simplePaginate(100);

            $agentOpeningBalanceFY = AgentOpeningBalanceFY::where('fys_id', $fys->id)
                ->where('agent_id', $request->agent_id)
                ->first();

            $amount = ($agentOpeningBalanceFY) ? $agentOpeningBalanceFY->amount : 0;
            foreach ($datas as $key => $val) {

                if ($val->type == 1) {
                    $credit_balance = $credit_balance + $val->amount;
                }
                if ($val->type == 2) {
                    $amount = $amount - $val->amount;
                }
                if ($val->type == 3) {
                    $amount = $amount + $val->amount;
                }
                if ($val->type == 4) {
                    $amount = $amount + $val->amount;
                }
                if ($val->type == 6) {
                    $amount = $amount - $val->amount;
                }
                if ($val->type == 5) {
                    $credit_balance =  $credit_balance  - $val->amount;
                }
                if ($val->type == 10) {
                    $credit_balance =  $amount  + $val->amount;
                }
                $val->closing_balance = $amount;
                $val->credit_balance  = $credit_balance;


                $date_from = Carbon::parse($request->start_date);
                $date_end  = Carbon::parse($request->end_date);
                if ($val->created_at <= $date_end && $val->created_at >= $date_from) {
                    array_push($data, $val);
                }
            }
            $data = collect($data);
        } else {
            $data = collect($data);
        }

        return view('accounts.agents-ledger.delete', compact('datas', 'agents', 'data'));
    }



    public function submitAgentLedgerDeletePage(Request $request, $id)
    {
        $resp = Credits::where('id', $id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Successfully Deleted'
        ]);
    }





    public function index(Request $request)
    {


        ini_set('max_execution_time', 3600);
        $data=[];
        $agent = null;
        // $financial_year = FY::OrderBy('id', 'DESC')->get();
        $date_from      = ($request->start_date) ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfDay();
        $date_end       =  ($request->end_date) ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay() ;

        $FY = FY::where('isActive' , 1)->first();
        $current_fy_start = Carbon::parse($FY->financial_year_start)->startOfDay();

        if(strtotime($date_from) < strtotime($current_fy_start) || strtotime($date_end) < strtotime($current_fy_start)) {
            $request->session()->flash('error','Invalid Date Range');
            $datas = collect([]);
           return view('accounts.agents-ledger.index', compact('datas', 'agent', 'data'));
        }elseif($date_from->format('Y-m-d') <= '2023-03-31' || $date_end->format('Y-m-d') <= '2023-03-31'){
                $request->session()->flash('success','Successfully Received');
                $datas = collect([]);
                return view('accounts.agents-ledger.index', compact('datas', 'agent', 'data', ));
        }


        if ($request->has('agent_id') && $request->agent_id != ''
        && $request->has('start_date') && $request->start_date != ''
        && $request->has('end_date') && $request->end_date != ''  )
        {
            $agent = Agent::find($request->agent_id);


            $datas = DB::table('account_transaction')
                            ->leftJoin('book_tickets','book_tickets.id','=','account_transaction.ticket_id')
                            ->leftJoin('air_ticket_refunds', function($join){
                                $join->on('air_ticket_refunds.book_ticket_id','=','book_tickets.id')
                                    ->where('air_ticket_refunds.created_at','=',DB::raw('account_transaction.created_at'));
                            })
                            ->orderBy('account_transaction.created_at', 'asc')
                            ->WhereIn('account_transaction.type', [1, 2, 3, 4, 5, 6,7,8,9,10,11])
                            ->where('account_transaction.agent_id',$request->agent_id)
                            ->whereNull('account_transaction.deleted_at')
                            ->whereBetween('account_transaction.created_at',[$date_from , $date_end])
                            ->select('account_transaction.type',
                                    'account_transaction.created_at',
                                    'account_transaction.amount',
                                    'book_tickets.bill_no',
                                    'account_transaction.reference_no',
                                    'account_transaction.id',
                                    'book_tickets.src',
                                    'book_tickets.dest',
                                    'book_tickets.travel_date',
                                    'book_tickets.airline',
                                    'book_tickets.pnr',
                                    'account_transaction.remarks',
                                    DB::raw('( SELECT concat(title, " ", first_name, " ", last_name) as refund_pax_name
                                            FROM book_ticket_details
                                            WHERE id = (JSON_UNQUOTE(JSON_EXTRACT(air_ticket_refunds.passenger_ids, "$[0]")))) AS first_element'),
                                    DB::raw('(book_tickets.adults+book_tickets.child+book_tickets.infants) as pax_count'),
                                    DB::raw('(air_ticket_refunds.adult+air_ticket_refunds.child+air_ticket_refunds.infant) as refund_pax_count'),
                                    DB::raw('(SELECT concat(title," ",first_name," ",last_name) as pax_name from book_ticket_details where book_ticket_id = book_tickets.id LIMIT 1 ) as pax_name')
                            )
                            ->get();

            $balance = $this->getFYSOpeningBal($request->agent_id , $FY->id);

            $results = [];

            if(!empty($datas)) {
                foreach($datas as $item) {
                    $refund = 0;

                    if($item->type == 1){
                        $item->credit =  $item->amount;
                        $item->reference_no =   $item->reference_no ;
                    }
                    if($item->type == 5){
                        $item->debit =  $item->amount;
                        $item->reference_no =   $item->reference_no ;
                    }
                    if($item->type == 9){
                        $item->credit =  $item->amount;
                        $item->reference_no =   $item->bill_no ;
                    }
                    if($item->type == 6){
                        $item->debit =  $item->amount;
                        $item->reference_no =   $item->bill_no;
                        $balance = $balance + $item->amount;
                    }
                    if($item->type == 3){
                        $item->credit =  $item->amount;
                        $item->reference_no =  'RCPT-'. $item->id;
                        $balance = $balance + $item->amount;

                    }
                    if ($item->type == 4) {
                        $balance = $balance + $item->amount;
                        $item->credit =  $item->amount;
                        $item->reference_no =   $item->bill_no ;
                    }

                    if ($item->type == 2 ) {
                        $item->debit =  $item->amount;
                        $item->reference_no =  $item->bill_no ;
                        $balance = $balance - $item->amount;
                    }

                    if ($item->type == 7) {
                        $item->credit =  $item->amount;
                        $item->reference_no =   $item->reference_no;
                        $balance = $balance + $item->amount;
                    }

                    if ($item->type == 8) {
                        $item->debit =  $item->amount;
                        $item->reference_no =   $item->bill_no ;
                        $balance = $balance - $item->amount;
                    }

                    if ($item->type == 10) {
                        $item->debit =  $item->amount;
                        $item->reference_no =  'DEBT-'. $item->id;
                        $balance = $balance - $item->amount;
                    }
                    if ($item->type == 11) {
                        $item->credit =  $item->amount;
                        $item->reference_no =  'CREDIT-'. $item->id;
                        $balance = $balance + $item->amount;
                    }

                    $item->balance = $balance;
                    $item->refund = $refund;

                    if(strtotime($item->created_at) >= strtotime($date_from) ) {
                        array_push($results, $item);
                    }
                    $pnr = $item->pnr;
                    $res = json_decode($pnr, true);
                    
                    if ($res !== null) {
                        $item->pnr = implode(",", $res);
                    } else {
                        $item->pnr = $pnr;
                    }   
                }

            }

            $datas = collect($results);


        } else {
            $datas = collect([]);
        }

        return view('accounts.agents-ledger.index', compact('datas', 'agent', 'data'));
    }





    public function getRefundCount($ticket_id , $created_at) {
        $date = date('Y-m-d', strtotime($created_at));

        $query = "SELECT pax FROM air_ticket_refunds ATR  WHERE ATR.book_ticket_id = $ticket_id AND DATE(ATR.created_at) = '".$date."' ";

        $res = DB::select(DB::raw($query));

        return isset($res[0]) ? $res[0]->pax : 0;
    }


    function getFYSOpeningBal($agent_id , $fys_id=4) {
        $query = "SELECT * FROM agent_opening_balance_f_y_s AOB, f_y_s FY
                  WHERE AOB.fys_id = FY.id AND FY.id = $fys_id AND
                  AOB.agent_id = $agent_id";

        $res = DB::select(DB::raw($query));

        return isset($res[0]) ? $res[0]->amount : 0;
    }



    public function calculateAgentLedger(Request $request) {

        $offset = $request->get('offset');
        $limit = $request->get('limit');
        $agents = Agent::where('status', 1)->offset($offset)->limit($limit)->pluck('id');
        $balance_data = [];

        $start_date = Carbon::parse('2022-04-01')->startOfDay();
        $end_date   = Carbon::parse('2023-03-31')->endOfDay();

        foreach($agents as $agent_id) {
            $q     = Credits::orWhereIn('type', [1, 2, 3, 4, 5, 6, 7, 8]);
            $q->where('agent_id', $agent_id);
            $q->whereBetween('created_at',[$start_date , $end_date]);
            $datas =  $q->orderBy('created_at', 'asc')->get();
            $balance = $this->getFYSOpeningBal($agent_id , 4);

            if(!empty($datas)) {
                foreach($datas as $item) {
                    if ($item->type == 3 || $item->type == 4) {
                        $balance = $balance + $item->amount;
                    }
                    if ($item->type == 2 || $item->type == 6) {
                        $balance = $balance - $item->amount;
                    }
                    if ($item->type == 7) {
                        $balance = $balance + $item->amount;
                    }
                    if ($item->type == 8) {
                        $balance = $balance - $item->amount;
                    }
                }
            }

            $temp = [];
            $temp['fys_id'] = 5;
            $temp['agent_id'] = $agent_id;
            $temp['amount'] = $balance;
            $temp['isActive'] = 1;
            $temp['created_at'] = date('Y-m-d H:i:s');
            $temp['updated_at'] = date('Y-m-d H:i:s');
            array_push($balance_data, $temp);
        }

        if(count($balance_data) > 0) {
            AgentOpeningBalanceFY::insert($balance_data);
        }

        echo "Success";

    }


    public function getBookTicketSerialNo(Request $request)
    {
        $book_tickets = BookTicket::withTrashed()->get();
        foreach ($book_tickets as $key => $val) {
            $new_bill_no[$key] = 'GFS-' . (1 + $key);
            $count = (1 + $key);

            $val->update(['bill_no' => 'GFS-' . (1 + $key)]);
        }
        SerialCounter::where('name', 'sale')->update(['count' => $count]);
        return view('tickets.book_ticket_serial_no', compact('book_tickets', 'new_bill_no'));
        return $book_tickets;
    }


    public function getAgentAccount(Request $request)
    {
        $agents = $request->agents;


        $fys    = FY::where('isActive', 1)->first();



        foreach ($agents as $i => $val)
        {
            $agent = Agent::where('id', $agents[$i])->firstOrFail();

            $count = AgentOpeningBalanceFY::where('fys_id', $fys->id)
                ->where('agent_id', $agent->id)
                ->count();

            if ($count  >= 1) {
                AgentOpeningBalanceFY::where('fys_id', $fys->id)
                    ->where('agent_id', $agents[$i])
                    ->update([
                        'amount' => $request->amount[$i]
                    ]);
            } else {
                $agent_opening_balace = AgentOpeningBalanceFY::create([
                    'fys_id' => $fys->id,
                    'agent_id' => $agents[$i],
                    'amount' => $request->amount[$i]
                ]);
            }
            $q = Credits::orderBy('created_at', 'asc')
                ->orWhereIn('type', [1, 2, 3, 4, 5, 6,7,8])
                ->where('agent_id', $agents[$i])
                ->whereDate('created_at','>','2022-03-31');

            $datas =  $q->get();




            $amount = $request->amount[$i];

            $credit_balance = 0;

            foreach ($datas as $key => $val) {
                if ($val->type == 1) {
                    $credit_balance = $credit_balance + $val->amount;
                }
                if ($val->type == 2) {
                    $amount        = $amount - $val->amount;
                }
                if ($val->type == 3) {
                    $amount = $amount + $val->amount;
                }
                if ($val->type == 4) {
                    $amount = $amount + $val->amount;
                }
                if ($val->type == 6) {
                    $amount        = $amount - $val->amount;
                }
                if ($val->type == 5) {
                    $credit_balance =  $credit_balance  - $val->amount;
                }
                if ($val->type == 7) {
                    $amount =  $amount + $val->amount;
                }
                if ($val->type == 8) {
                    $amount =  $amount  - $val->amount;
                }

                $val->update([
                    'balance' => $amount
                ]);
            }


            // modificiation
//            $checkDebitNote = Credits::where('agent_id', $agents[$i])
//                ->where('remarks', 'SYSTEM - AUTO RESET OF THE TEMPORARY CREDIT')->count();
//
//            if ($checkDebitNote == 0) {
//                // insert a record
//                $data = [
//                    'remarks' => 'SYSTEM - AUTO RESET OF THE TEMPORARY CREDIT',
//                    'agent_id' => $agents[$i],
//                    'type' => 5,
//                    'amount' => $credit_balance,
//                    'owner_id' => Auth::id(),
//                    'reference_no' => CreditService::generateReferenceNo()
//                ];
//                Credits::create($data);
//                // agent credit_Balance to 0
//                $agent->update([
//                    'credit_balance' => 0
//                ]);
//            }
        }
        return view('accounts.agents-ledger.list', compact('datas'));
    }

    public function getAgentAccountPage(Request  $request)
    {
        $agentsDesign = Agent::get();

        $q  = Agent::where('status', 1);

        if ($request->has('agent_id')) {
            $q->whereIn('id', $request->agent_id);
        }

        $agents =  $q->simplePaginate(20);

        return view('accounts.agents-ledger.view', compact('agents', 'agentsDesign'));
    }

    public function updatePurchaseEntry()
    {

        $purchase_entries = PurchaseEntry::whereDate('travel_date', '=', date('2021-01-10'))
            ->orderBy('travel_date', 'DESC')
            ->get();
        foreach ($purchase_entries as $val) {
            $name_list_status = NameListStatus::orderBy('created_at', 'DESC')
                ->where('purchase_entry_id', $val->id)
                ->get();
            $val->update([
                'namelist_status' => 0
            ]);
            if ($name_list_status->count() > 0) {
                $val->update([
                    'namelist_status' => $name_list_status[0]->type
                ]);
            }
        }
    }

    public function getledgerTest()
    {

        //ticket  (pax_price * adult + child)) = Credit amount
        $defectCal = [];
        $book_tickets = BookTicket::orderBy('id', 'DESC')
            ->whereMonth('book_tickets.created_at', '>', 3)
            ->whereYear('book_tickets.created_at', 2021)
            ->skip(1000)->limit(500)
            ->get();

        foreach ($book_tickets as $key => $book_ticket)
        {
            $credits = Credits::where('ticket_id', $book_ticket->id)->first();
            if ($credits) {
                $total_amount = ($book_ticket->infants + $book_ticket->adults) * $book_ticket->pax_price;
                if ($credits->amount != $total_amount) {
                    $credits->update(['amount' =>  $total_amount]);
                    array_push($defectCal, $book_ticket);
                }
            }
        }
        return $defectCal;

        $agent_id = 5;
        // $receipts = Receipt::where('agent_id',$agent_id)->get();
        // foreach($receipts as $key => $receipt) {
        //     Credits::create([
        //         'agent_id' => $agent_id,
        //         'type' =>3,
        //         'amount' => $receipt->amount,
        //         'remarks' => $receipt->remarks,
        //         'owner_id'=> 1,
        //         'reference_no' => $receipt->receipt_no,
        //         'created_at' => Carbon::parse($receipt->date)
        //     ]);
        // }

        $opening_balance = -147488;
        $credit_balance  = 0;

        $agents = Agent::find($agent_id);


        $data   = Credits::orderBy('created_at', 'ASC')
            ->whereMonth('created_at', '>', 3)
            ->whereYear('created_at', 2021)
            ->where('agent_id', $agent_id)
            ->simplePaginate(100);




        $agents->update([
            'opening_balance' => $opening_balance,
            'credit_balance' => $credit_balance,
        ]);
        return view('accounts.agents-ledger.test', compact('data', 'agents'));
    }

    private function getBalance($opening_balance, $credit_balance, $amount)
    {
        // when opening balance is greater than sale amount
        if ($opening_balance >= $amount) {
            $opening_balance = $opening_balance - $amount;
        } // when opening balance is less than 0
        elseif ($opening_balance <= 0) {
            // when credit balance is greater equal to sale amount
            if ($credit_balance >= $amount) {
                $credit_balance = $credit_balance - $amount;
            }
        } // when sum of credit balance + opening_balance is greater equal to sale amount
        elseif ($opening_balance + $credit_balance >= $amount) {
            $opening_balance = $remaining_amount = $amount - $opening_balance;
            $credit_balance  =   $credit_balance - $remaining_amount;
        } else {
            dd("ERR");
        }
        return [
            'opening_balance' =>  $opening_balance,
            'credit_balance' => $credit_balance
        ];
    }

    public function showDistributorLedgerPage(Request  $request){
        $distributors = Agent::where('type',2)->simplePaginate(50);
        $agent = null;

        if($request->has('distributor_id') && $request->distributor_id != '')
        {
            $agent = Agent::find($request->distributor_id);
            $distributor_id = $request->distributor_id;
            $users = Agent::where('agents.id',$distributor_id)
                ->join('users','users.email','=','agents.email')
                ->select('users.id')->first();


            $q= Credits::where(function ($query) use ($users,$distributor_id) {
                $query->where('owner_id', '=', $users->id)
                    ->orWhere('agent_id', '=', $distributor_id);
            })
                ->orderBy('created_at','ASC');


            $data = $q->get();

            $balance = 0;
            $datas = [];
            foreach($data as $key => $val)
            {
                if( $val->type == 7)
                    $balance = $balance - $val->amount;
                elseif($val->type == 3 || $val->type == 8)
                    $balance = $balance + $val->amount;
                else
                    $balance = $balance;

                $val->balance =  $balance ;

                $date_from = Carbon::parse($request->start_date)->startOfDay();
                $date_end  = Carbon::parse($request->end_date)->endOfDay();
                if($date_from->format('Y-m-d') <= '2023-03-31' ||
                    $date_end->format('Y-m-d') <= '2023-03-31'
                ){
                    $datas = collect([]);
                    return view('accounts.distributor-ledger.index',compact('distributors','datas','agent'));
                }



                if ($val->created_at <= $date_end && $val->created_at >= $date_from) {
                    array_push($datas, $val);
                }
            }
            $datas = collect($datas);

        }else{

            $datas = collect([]);
        }


        return view('accounts.distributor-ledger.index',compact('distributors','datas','agent'));
    }

    public function agentLedgerCalculation(Request $request){

          ini_set('max_execution_time', 3600);

            $offset = $request->offset;
            $limit   = $request->limit;
            $agent_id = $request->agent_id;



            if($request->has('agent_id'))
            {
                $agents =  DB::table('agents')
                            ->select('id','code')
                            ->where('id',$agent_id)
                            ->get();

            }elseif($request->has('limit') && $request->has('offset')){
                $agents =  DB::table('agents')
                            ->select('id','code')
                            ->orderby('id','ASC')

                            ->offset($offset)
                            ->limit($limit)
                            ->get();
            }else{
                dd("Pass agent_id or offset and limit");
            }





            foreach($agents as $k => $a)
            {
                    $agent_opening_balance =  DB::table('agent_opening_balance_f_y_s')->where('fys_id',5)->where('agent_id',$a->id)->first();

                    if(!$agent_opening_balance){
                        $agent_opening_balance = 0;
                    }else{
                        $agent_opening_balance = $agent_opening_balance->amount;
                    }

                    $account_transactions = DB::table('account_transaction')
                                            ->select('id','type','amount')
                                            ->whereDate('created_at','>=','2023-04-01')
                                            ->where('agent_id',$a->id)
                                            ->whereNull('deleted_at')
                                            ->get();

                    foreach($account_transactions as $ky => $at){

                        if ($at->type == 3 || $at->type == 4) {
                            $agent_opening_balance = (float)$agent_opening_balance + (float)$at->amount;
                        }

                        if ($at->type == 2 || $at->type == 6) {
                            $agent_opening_balance = (float)$agent_opening_balance - (float)$at->amount;
                        }

                        if ($at->type == 7) {
                            $agent_opening_balance = (float)$agent_opening_balance + (float)$at->amount;
                        }

                        if ($at->type == 8) {
                            $agent_opening_balance = (float)$agent_opening_balance - (float) $at->amount;
                        }

                        if ($at->type == 10) {
                            $agent_opening_balance = (float)$agent_opening_balance - (float)$at->amount;
                        }
                        if ($at->type == 11) {
                            $agent_opening_balance = (float)$agent_opening_balance + (float) $at->amount;
                        }

                       // echo $at->amount . ' '. $agent_opening_balance. ' '.$at->type.'<br>';


                    }
                    $agents = DB::table('agents')->where('id',$a->id)->first();

                    // DB::table('agents')->where('id',$a->id)->update([
                    //     'opening_balance' => $agent_opening_balance,
                    //     'opening_balance_bkp' => $agents->opening_balance
                    // ]);
                    echo $a->id . ' '. $a->code. ' '.$account_transactions->count().'<br>';
            }


    }

    public function  agentLedgerClosingBalanceCalculation(Request $request){
        ini_set('max_execution_time', 3600);

        $closing_balance_date = '2023-10-13';
        $opening_balance_date = '2023-04-01';
        $closing_balance_date_1 = '2023-10-15';

        $offset = $request->offset;
        $limit   = $request->limit;
        $agent_id = $request->agent_id;



        if($request->has('agent_id'))
        {
            $agents =  DB::table('agents_12102023')
                        ->select('id','code')
                        ->where('has_api',1)
                        ->where('id',$agent_id)
                        ->get();

        }elseif($request->has('limit') && $request->has('offset')){
            $agents =  DB::table('agents_12102023')
                        ->select('id','code')
                        ->where('has_api',1)
                        ->orderby('id','ASC')
                        ->offset($offset)
                        ->limit($limit)
                        ->get();
        }else{
            dd("Pass agent_id or offset and limit");
        }


        foreach($agents as $k => $a)
        {

                $agent_opening_balance =  DB::table('agent_opening_balance_f_y_s_12102023')->where('fys_id',5)->where('agent_id',$a->id)->first();

               // $agent_opening_balance =  DB::table('agent_closing_balance_report')->where('agent_id',$a->id)->first();

                if(!$agent_opening_balance){
                    $agent_opening_balance = 0;
                }else{
                    $agent_opening_balance = $agent_opening_balance->amount;
                }
                $account_closing_balance = DB::table('account_transaction_131023')
                                        ->select('id','type','balance','amount')
                                        ->whereBetween('created_at',[$opening_balance_date,$closing_balance_date_1])
                                        ->where('agent_id',$a->id)
                                        ->whereNull('deleted_at')
                                        ->orderBy('id','ASC')
                                        ->get();


                foreach($account_closing_balance as $ky => $at){

                    if ($at->type == 3 || $at->type == 4) {
                        $agent_opening_balance = (float)$agent_opening_balance + (float)$at->amount;
                    }

                    if ($at->type == 2 || $at->type == 6) {
                        $agent_opening_balance = (float)$agent_opening_balance - (float)$at->amount;
                    }

                    if ($at->type == 7) {
                        $agent_opening_balance = (float)$agent_opening_balance + (float)$at->amount;
                    }

                    if ($at->type == 8) {
                        $agent_opening_balance = (float)$agent_opening_balance - (float) $at->amount;
                    }

                    if ($at->type == 10) {
                        $agent_opening_balance = (float)$agent_opening_balance - (float)$at->amount;
                    }
                    if ($at->type == 11) {
                        $agent_opening_balance = (float)$agent_opening_balance + (float) $at->amount;
                    }




                }
                echo "AGENT ID ". $a->id .' CLOSING BALANCE - '. $agent_opening_balance . ' date ' .  $closing_balance_date;
                echo "<br>";
                DB::table('agent_closing_balance_report')->insert([
                    'agent_id' => $a->id,
                    'amount' => $agent_opening_balance,
                    'date' => $closing_balance_date
                ]);

                // echo $a->id . ' '. $a->code. ' '.$account_transactions->count().'<br>';
        }

    }
}
