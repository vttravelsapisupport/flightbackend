<?php

namespace App\Http\Controllers\FlightTicket\Accounts;

use App\Http\Controllers\Controller;
use App\Models\DepositRequest;
use App\Models\FlightTicket\Accounts\Receipt;
use App\Models\FlightTicket\Agent;
use App\Models\FlightTicket\Credits;
use App\Services\AgentService;
use App\Services\CreditService;
use App\Services\ReceiptService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DepositRequestController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:deposit-request show', ['only' => ['index']]);
        $this->middleware('permission:deposit-request approve|deposit-request reject', ['only' => ['update']]);
    }

    public function index(Request $request)
    {
        $agents =  Agent::where('status', 1)->get();

        $q = DepositRequest::orderBy('id', 'DESC');
        if ($request->has('agent_id') && $request->agent_id) {
            $q->where('agent_id', $request->agent_id);
        }

        if ($request->has('bank_id') && $request->bank_id) {
            $q->where('bank', $request->bank_id);
        }
        if ($request->has('amount') && $request->amount) {
            $q->where('amount', $request->amount);
        }
        if ($request->has('status') && $request->status) {
            $q->where('status', $request->status);
        }


        if ($request->has('deposit_from') && $request->deposit_from && $request->has('deposit_to') && $request->deposit_to) {
            $from = Carbon::parse($request->deposit_from)->startOfDay();
            $to   = Carbon::parse($request->deposit_to)->endOfDay();
            $q->whereBetween('created_at', [$from, $to]);

            if($from->format('Y-m-d') <= '2023-03-31' ||
                $to->format('Y-m-d') <= '2023-03-31'
            ){
                $datas = collect([]);
                return view('accounts.deposit-requests.index', compact('datas', 'agents'));
            }
        }else{
            $q->whereDate('created_at', '>=','2023-04-04=1');
        }
        $datas = $q->simplePaginate(50);


        return view('accounts.deposit-requests.index', compact('datas', 'agents'));
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
     * @param  \App\DepositRequest  $depositRequest
     * @return \Illuminate\Http\Response
     */
    public function show(DepositRequest $depositRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DepositRequest  $depositRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(DepositRequest $depositRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DepositRequest  $depositRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


        $status         = $request->status;
        $depositRequest = DepositRequest::find($id);

        if ($status  == 3) {
            $depositRequest->update(['status' => $status]);
            return response()->json(['success' => true, 'message' => 'Successfully Updated']);
        } elseif ($status  == 2) {
            $agent_details = Agent::find($depositRequest->agent_id);
            $old_credit    = Credits::where('agent_id', $depositRequest->agent_id)
                ->whereIn('type', [1, 2, 3, 4, 5, 6])
                ->orderBy('id', 'DESC')
                ->first();
            $old_balance = 0;
            if ($old_credit) {
                $old_balance = $old_credit->balance;
            }
            $new_balance = $old_balance + $depositRequest->amount;
            $data_credit = [
                'remarks' =>  'Deposit Requests Approved',
                'agent_id' => $depositRequest->agent_id,
                'type' => 3, // receipt
                'amount' => $depositRequest->amount,
                'balance' => $new_balance,
                'owner_id' => Auth::id(),
                'date' =>  $depositRequest->created_at,
                'reference_no' => CreditService::generateReferenceNo(),
                'created_at' => Carbon::parse($depositRequest->created_at)
            ];
            $data_receipt = [
                'agent_id' => $depositRequest->agent_id,
                'amount' => $depositRequest->amount,
                'payment_mode' => ($depositRequest->type == 'cash') ? 1 : 2,
                'image' => $depositRequest->files,
                'refernence_no' => $depositRequest->ref_number,
                'receipt_no' => ReceiptService::generateReceiptNo(),
                'status' => 1,
                'owner_id' => Auth::id(),
                'remarks' => 'Deposit Requests Approved',
                'date' => Carbon::parse($depositRequest->created_at)
            ];

            //


            $result = DB::transaction(function () use ($data_credit, $status, $depositRequest, $data_receipt, $agent_details) {

                $receipts = Receipt::where('agent_id', $depositRequest->agent_id)->where('amount', $depositRequest->amount)->orderby('id', 'DESC')->first();
                if ($receipts) {
                    $date = $receipts->created_at;
                    $now  = Carbon::now();

                    $diff = $date->diffInSeconds($now);
                    if ($diff > 300) {
                        $creditResp  = Credits::create($data_credit);
                        $data_receipt['account_transaction_id'] = $creditResp->id;
                        Receipt::create($data_receipt);

                        $agent_details->increment('opening_balance', $depositRequest->amount);
                        $depositRequest->update(['status' => $status]);

                        // Update credit balance based on credit limit for agent
                        if ($agent_details->credit_limit > 0) {
                            AgentService::updateCreditBalanceBasedOnCreditLimt($agent_details->id, 1, $depositRequest->amount);
                        }

                        return ['success' => true, 'message' => 'Successfully Updated'];
                    } else {
                        return ['success' => false, 'message' => 'Error: Same amount receipt is present'];
                    }
                } else {
                    Receipt::create($data_receipt);
                    Credits::create($data_credit);
                    $agent_details->increment('opening_balance', $depositRequest->amount);
                    $depositRequest->update(['status' => $status]);

                    // Update credit balance based on credit limit for agent
                    if ($agent_details->credit_limit > 0) {
                        AgentService::updateCreditBalanceBasedOnCreditLimt($agent_details->id, 1, $depositRequest->amount);
                    }
                    return ['success' => true, 'message' => 'Successfully Updated'];
                }
            });
            return response()->json($result);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DepositRequest  $depositRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(DepositRequest $depositRequest)
    {
        //
    }
}
