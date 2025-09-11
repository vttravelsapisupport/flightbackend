<?php

namespace App\Http\Controllers\FlightTicket\Accounts;

use App\User;
use Illuminate\Http\Request;
use App\Models\FlightTicket\Agent;
use App\Http\Controllers\Controller;
use App\Models\FlightTicket\Airport;
use Carbon\Carbon;
use App\Models\Transaction;

class DebitorListController extends Controller
{

    public function __construct() {
        $this->middleware('permission:debitor-list show', ['only' => ['index']]);
    }

    public function index(Request $request)
    {
        $agent = '';
        $limit= 30;
        $airports = Airport::where('status',1)->distinct('code')->get();
        $sale_rep = User::role([1,2,3,5,7,6,9])->get();

        $q = Agent::orderBy('company_name','asc');
        if($request->has('agent_id') && $request->agent_id != ''){
            $agent = Agent::find($request->agent_id);
            $q->where('id', $request->agent_id);
        }
        if($request->has('airport') && $request->airport != ''){
            $q->where('nearest_airport', $request->airport);
        }
        if($request->has('account_manager_id') && $request->account_manager_id != ''){
            $q->where('account_manager_id', $request->account_manager_id);
        }


        if($request->has('result')  && $request->result != ''){
            $limit = $request->result;
        }

        if($request->has('city')  && $request->city != ''){
            $q->where('city', 'like','%'.$request->city.'%');
        }
        if($request->exclude_zero == 1 && $request->has("exclude_zero") ){
            $q->where('opening_balance','<>',0);
        }

        if($request->exclude_positive == 1 && $request->has("exclude_positive") ){
            $q->where(function ($query) {
                $query->where('opening_balance', '<', 0)
                      ->orWhere('opening_balance', '=', 0);
            });
        }
        if($request->exclude_negative == 1 && $request->has("exclude_negative") ){
            $q->where(function ($query) {
                $query->where('opening_balance', '>', 0)
                      ->orWhere('opening_balance', '=', 0);
            });
        }

        if ($request->has('date_from') && $request->date_from != '' && $request->has('date_to') && $request->date_to != '') {
            $from = Carbon::parse($request->date_from);
            $to   = Carbon::parse($request->date_to);
            $q->whereBetween('agents.created_at', [$from, $to]);

            if($from->format('Y-m-d') <= '2023-03-31' ||
                $to->format('Y-m-d') <= '2023-03-31'
            ){
                $data = collect([]);
                return view('accounts.debitor-list.index',compact('agent','data','airports','sale_rep'));
            }
        }

        $data = $q->paginate($limit);

        return view('accounts.debitor-list.index',compact('agent','data','airports','sale_rep'));
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
