<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\AgentPayment;
use Illuminate\Http\Request;
use App\Services\AgentService;
use App\Services\ReceiptService;
use App\Models\FlightTicket\Agent;
use App\Models\FlightTicket\Credits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\FlightTicket\Accounts\Receipt;
use App\Models\FlightTicket\Accounts\CompanyBankDetail;

class AgentPaymentController extends Controller
{
    public function __construct() {
        $this->middleware('permission:receipt show', ['only' => ['index']]);
    }

    public function index(Request  $request)
    {
        $receipts     =  AgentPayment::whereNull('date')->get();
        $agent = '';
        foreach($receipts as $key => $val){
            $val->update(['date' =>  $val->created_at]);
        }
        $agents =  Agent::where('status',1)->get();
        $q     =  AgentPayment::orderBy('date','DESC');

        if($request->has('agent_id') && $request->agent_id != '')

            $agent = Agent::find($request->agent_id);

        $q->where('agent_id',$request->agent_id);

        if($request->has('start_date') && $request->has('end_date')){
            $start_date =  Carbon::parse($request->start_date);
            $end_date   =  Carbon::parse($request->end_date);
            $q->whereBetween('date',[$start_date, $end_date->endOfDay()]);

            if($start_date->format('Y-m-d') <= '2023-03-31' ||
                $end_date->format('Y-m-d') <= '2023-03-31'
            ){
                $datas = collect([]);
                return view('accounts.agent-payment.index',compact('agents','datas','agent'));
            }
        }else{
            $q->whereDate('date',Carbon::now());
        }

        $datas = $q->simplePaginate(50);

        return view('accounts.agent-payment.index',compact('agents','datas','agent'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $agentServiceObj      =  new AgentService();
        $agents               = $agentServiceObj->getAllActiveAgents();

        $bank_details = CompanyBankDetail::where('status',0)->pluck('bank_name','id')->all();
        return view('accounts.agent-payment.create',compact('agents','bank_details'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'agent_id' => 'required|integer',
            'payment_mode' => 'required|integer',
            'amount' => 'required',
            'date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $sevenDaysAgo = Carbon::now()->subDays(7);
                    if (Carbon::parse($value)->lt($sevenDaysAgo)) {
                        $fail("The $attribute must be within the last 7 days.");
                    }
                },
            ],
        ]);
        $image_path = '';
        $data = $request->all();

        if($request->has('image')){
            $image_path = Storage::disk('s3')->put('agents',$request->image);
            $data['image'] = $image_path;
        }
        $receipt_no = AgentService::generatePaymentReceiptNo();

        $data['image'] = $image_path;
        $data['receipt_no'] = $receipt_no;
        $data['status'] = 1;
        $data['owner_id'] = Auth::id();
        $data['date']   = Carbon::parse($request->date);

        $old_credit = Credits::where('agent_id',$request->agent_id)
            ->whereIn('type', [1, 2, 3, 4, 5, 6,10])
            ->orderBy('id','DESC')
            ->first();

        $old_balance = 0 ;
        if($old_credit){
            $old_balance = $old_credit->balance;
        }
        $new_balance = $old_balance + $request->amount;

        $data_credit = [
            'remarks' => $request->remarks,
            'agent_id' => $request->agent_id,
            'type' => 10, // debit
            'amount' => $request->amount,
            'balance' => $new_balance,
            'owner_id' => Auth::id(),
            'date' => Carbon::now(),
            'reference_no' => $receipt_no
        ];

        try {

            $creditResp = Credits::create($data_credit);
            $data['account_transaction_id'] = $creditResp->id;
            AgentPayment::create($data);
            AgentService::updateOpeningBalance($request->agent_id,2, $request->amount);

            // Update credit balance based on credit limit for agent
            $agent = Agent::where('id', $request->agent_id)->first();
            if($agent->credit_limit > 0) {
                AgentService::updateCreditBalanceBasedOnCreditLimt($request->agent_id, 1, $request->amount);
            }

            $request->session()->flash('success','Successfully Saved');
            return  redirect(route('agent-payments.index'));

        } catch (Throwable $e) {
            report($e);
            return response()->json([
                "success" => false,
                "message" => 'Error',
                "data" => $e
            ]);
        }

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data  =  AgentPayment::find($id);
        return view('accounts.agent-payment.show',compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $receipts =  Receipt::find($id);
        // $agentServiceObj      =  new AgentService();
        // $agents               = $agentServiceObj->getAllActiveAgents();
        // $bank_details = CompanyBankDetail::where('status',0)->pluck('bank_name','id')->all();

        // return view('accounts.agent-payment.edit',compact('agents','bank_details','receipts'));
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
        //return $request->all();
        $old_receipts = Receipt::find($id);

        $credit = Credits::where('agent_id',$old_receipts->agent_id)
            ->where('amount',$old_receipts->amount)
            ->where('type',3)
            ->whereDate('created_at',$old_receipts->date)
            ->first();

        $old_agent= Agent::find($old_receipts->agent_id);
        $new_agent= Agent::find($request->agent_id);

        $receipts = Receipt::find($id);

        $credit->update([
            'reference_no' => $old_receipts->receipt_no,
            'amount' => $request->amount,
            'agent_id' => $request->agent_id,
            'created_at' => Carbon::parse($request->date)
        ]);

        $receipts->update([
            'amount' => $request->amount,
            'date' => Carbon::parse($request->date),
            'agent_id' => $request->agent_id,
            'reference_no' => $request->reference_no,
            'bank_id' => $request->bank_id,
            'payment_mode'=> $request->payment_mode
        ]);
        // old amount is bigger
        if($old_receipts->amount >= $request->amount){
            $difference = $old_receipts->amount - $request->amount;
            // agent id updated
            if($old_agent->id != $new_agent->id){
                // deduct from old agent id

                $old_agent->decrement('opening_balance',$old_receipts->amount);
                $new_agent->increment('opening_balance',$request->amount);
                if($new_agent->credit_limit > 0) {
                    AgentService::updateCreditBalanceBasedOnCreditLimt($new_agent->id, 1, $request->amount);
                }
            }else{
                $new_agent->decrement('opening_balance',$difference);
            }
        } // old amount is lesser
        else{
            $difference = $request->amount - $old_receipts->amount;
            // agent id updated
            if($old_agent->id != $new_agent->id){
                // decrease the amount with the receipt amount with old agent id
                $old_agent->decrement('opening_balance',$old_receipts->amount);
                // increase the amount with recipt amount  with new agent id
                $new_agent->increment('opening_balance',$request->amount);

                if($new_agent->credit_limit > 0) {
                    AgentService::updateCreditBalanceBasedOnCreditLimt($new_agent->id, 1, $request->amount);
                }
            }else{
                $new_agent->increment('opening_balance',$difference);
                if($new_agent->credit_limit > 0) {
                    AgentService::updateCreditBalanceBasedOnCreditLimt($new_agent->id, 1, $difference);
                }
            }
        }

        $request->session()->flash('success','Successfully Saved');
        return  redirect(route('agent-payment.show',$id));
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


    public function updateReceiptStatus(Request $request, $id){

        $receipt = Receipt::find($id);

        if($request->status == 2) {
            $receipt->update(['status' => $request->status]);
        }else{
            $receipt->update(['status' => $request->status]);
            $old_credit = Credits::where('agent_id', $request->agent_id)
                ->whereIn('type', [1, 2, 3, 4, 5, 6])
                ->orderBy('id','DESC')
                ->first();

            $old_balance = 0 ;
            if($old_credit){
                $old_balance = $old_credit->balance;
            }
            $new_balance = $old_balance + $receipt->amount;
            $receipt_no = ReceiptService::generateReceiptNo();
            $data_credit = [
                'remarks' => 'Amount '.$receipt->amount.' <a href="/accounts/receipts/'.$receipt->id.'" target="_blank">'.$receipt->receipt_no.' </a>is not received in the bank account - '.Auth::user()->first_name,
                'agent_id' => $request->agent_id,
                'type' => 10, // Debit
                'amount' => $receipt->amount,
                'balance' => $new_balance,
                'owner_id' => Auth::id(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'reference_no' => $receipt->receipt_no
            ];
            Credits::create($data_credit);
            AgentService::updateOpeningBalance($request->agent_id, 2 , $receipt->amount);
        }

        return response()->json(['success' => true, 'message' => 'Successfully Updated']);
    }
}
