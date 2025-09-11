<?php

namespace App\Http\Controllers\FlightTicket\Accounts;

use App\Http\Controllers\Controller;
use App\Mail\CreditRequestRemark;
use App\Models\CreditRequest;
use App\Models\FlightTicket\Agent;
use App\Models\FlightTicket\Credits;
use App\Services\AgentService;
use App\Services\CreditService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CreditRequestController extends Controller
{

    public function __construct() {
        $this->middleware('permission:credit-request show', ['only' => ['index']]);
    }

    public function index(Request $request)
    {
        $agent = '';
        $agents =  Agent::where('status', 1)->get();

        $q = CreditRequest::whereDate('created_at','>=','2023-04-01')->orderBy('id', 'DESC');

        if ($request->has('agent_id') && $request->agent_id) {
            $agent = Agent::find($request->agent_id);

            $q->where('agent_id', $request->agent_id);
        }
        if ($request->has('amount') && $request->amount) {
            $q->where('amount', $request->amount);
        }
        if ($request->has('status') && $request->status) {
            $q->where('status', $request->status);
        }

        $datas = $q->simplePaginate(50);


        return view('accounts.credit-requests.index', compact('datas', 'agents','agent'));
    }




    public function update(Request $request, $id)
    {

        $creditRequest = CreditRequest::find($id);
        $status = $request->status;
        $user = Auth::user();

        if($creditRequest->status != 1 || $creditRequest->status == $status)
            return response()->json(['success' => false, 'message' => 'Unable to process the request'],400);

        // when status is rejected
        if ($status  == 3) {
            $creditRequest->status = $status;
            $creditRequest->owner_id = $user->id;
            $creditRequest->save();
            $html = view('accounts.credit-requests.credit-rejection-remarks')->with('credit_request_id', $id)->render();
            return response()->json(['success' => true, 'message' => $html]);
        }
        // when status is approved
        elseif($status == 2) {
            $expire_date = Carbon::now()->addDays(30);
            $credit_data = [
                'remarks' => $creditRequest->remarks .' - Credit Requests Approved REF #'. $creditRequest->id,
                'agent_id' => $creditRequest->agent_id,
                'type' => 1,
                'amount' => $creditRequest->amount,
                'owner_id' => Auth::id(),
                'exp_date' => $expire_date,
                'reference_no' => CreditService::generateReferenceNo()
            ];
            try {
                Credits::create($credit_data);
                $request->session()->flash('success', 'Successfully Saved');
                AgentService::updateCreditBalance($creditRequest->agent_id, 1, $creditRequest->amount);
                $creditRequest->status = $status;
                $creditRequest->owner_id = $user->id;
                $creditRequest->save();
                $request->session()->flash('success', 'Successfully Approved Credit Requests');
                return response()->json(['success' => true, 'message' => 'Successfully Updated']);
            } catch (\Exception $e) {
//                report($e);
                return response()->json(['success' => false, 'message' => $e->getMessage()],500);
            }
        }else{
            return response()->json(['success' => false, 'message' => 'Invalid Status'],400);
        }

    }


    public function updateRemarks(Request $request) {

        $creditRequest = CreditRequest::find($request->get('credit_request_id'));

        $agent = Agent::find($creditRequest->agent_id);
        $creditRequest->admin_remarks = $request->get('message');
        $creditRequest->save();

        $subject = "CREDIT REQUEST REMARKS";

        try{
            Mail::to($agent->email)

                ->send(new CreditRequestRemark($creditRequest, $subject, $request->get('message')));
        }catch(\Exception $e){

        }

        $request->session()->flash('success', 'Successfully Rejected Credit Requests');
        return response()->json(['success' => true, 'message' => 'Successfully Rejected Credit Requests']);
    }

}
