<?php

namespace App\Http\Controllers\FlightTicket\Accounts;

use App\Http\Controllers\Controller;
use App\Models\FlightTicket\Agent;
use App\Models\FlightTicket\Credits;
use App\Services\AgentService;
use App\Services\CreditService;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Models\Audit;

class CreditsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct() {
        $this->middleware('permission:credit show', ['only' => ['index']]);
        $this->middleware('permission:credit create', ['only' => ['create']]);
        $this->middleware('permission:credit update', ['only' => ['update']]);
    }

    public function history(Request $request){
            $data = [];
            $agent = '';
            if($request->agent_id != '' && $request->has('agent_id') ){
                $id = $request->agent_id;
                $agent = Agent::find($id);
                $d = DB::select("select * from `audits`
                                        where `auditable_id` = ".$id."
                                        and `auditable_type` in ('App\\\\Models\\\\FlightTicket\\\\Agent')
                                        AND JSON_CONTAINS_PATH(`old_values`, 'one', '$.credit_limit')
                                        AND date(created_at) >= '2023-04-01'
                                        order by `id` desc");

                foreach($d as  $k => $v) {
                        $user = User::find($v->user_id);

                        $data[] = (object)[
                            'created_at' => Carbon::parse($v->created_at),
                            'old_values' => ($v->old_values) ? json_decode($v->old_values) : '',
                            'new_values' => ($v->new_values) ? json_decode($v->new_values) : '',
                            'user' => ($v->user_id) ? $user->first_name . ' '.$user->last_name : '',
                        ];

                }



            }
            return view('accounts.credits-debits.history',compact('data','agent'));
    }
    public function index(Request $request)
    {
        $agent = null;

        $q = Credits::whereIn('account_transaction.type', [1, 5,7])
            ->join('agents','agents.id','=','account_transaction.agent_id')
            ->join('users','users.id','=','account_transaction.owner_id')

            ->orderby('account_transaction.id', 'DESC');

        if ($request->has('agent_id') && $request->agent_id != '') {
            $agent = Agent::find($request->agent_id);
            $q->where('account_transaction.agent_id', $request->agent_id);
        }
        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date =  Carbon::parse($request->start_date)->startOfDay();
            $end_date   =  Carbon::parse($request->end_date)->endOfDay();

            if($start_date->format('Y-m-d') <= '2023-03-31' ||
                $end_date->format('Y-m-d') <= '2023-03-31'
            ){
                $data = collect([]);
                return view('accounts.credits-debits.index', compact('data','agent'));
            }

            $q->whereBetween('account_transaction.created_at', [$start_date, $end_date->endOfDay()]);
        } else {
            $q->whereDate('account_transaction.created_at', Carbon::now());
        }

        $data = $q->select('account_transaction.reference_no',
        'agents.company_name',
        'account_transaction.id',
        'account_transaction.type',
        'account_transaction.amount',
        'account_transaction.created_at',
        'account_transaction.remarks',
        DB::raw('CONCAT(users.first_name," ",users.last_name) as name')
        )->simplePaginate(300);

        return view('accounts.credits-debits.index', compact('data','agent'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $agents =  Agent::where('status', 1)->orderBy('id', 'DESC')->get();
        return view('accounts.credits-debits.create', compact('agents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $this->validate($request, [
            'agent_id' => 'required',
            'type' => 'required|in:1,5',
            'amount' => 'required|integer|between:0,9999999'
        ]);


        $agent = Agent::find($request->agent_id);


        if ($agent->credit_balance < $request->amount && $request->type == 5) {
            $request->session()->flash('error',"Credit balance is less that deducted Debit Balance");
            return redirect()->back()->withInput();
        }

        $expire_date = Carbon::now()->addDays($request->expire_day);


        $data = [
            'remarks' => $request->remarks,
            'agent_id' => $request->agent_id,
            'type' => $request->type,
            'amount' => $request->amount,
            'owner_id' => Auth::id(),
            'exp_date' => $expire_date,
            'reference_no' => CreditService::generateReferenceNo()
        ];

        try {
            $resp = Credits::create($data);
            $request->session()->flash('success', 'Successfully Saved');
            AgentService::updateCreditBalance($request->agent_id, $request->type, $request->amount);

            activity('Credit Added')
                ->performedOn($resp)
                ->event('created')
                ->log('Credit of Rs. ' .$request->amount.' is credited to '.$agent->company_name.' ('.$agent->code.')');

            return redirect(route('credits-debits.index'));
        } catch (Throwable $e) {
            report($e);
            return redirect()->back();
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
        return view('accounts.credits-debits.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('accounts.credits-debits.edit');
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
