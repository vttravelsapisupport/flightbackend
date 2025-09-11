<?php

namespace App\Http\Controllers\FlightTicket\Accounts;

use App\Http\Controllers\Controller;
use App\Models\FlightTicket\Accounts\onlinePaymentLogs;
use App\Models\FlightTicket\Agent;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OnlineTransactionController extends Controller
{

    public function __construct() {
        $this->middleware('permission:online-transaction show', ['only' => ['index']]);
    }

    public function index(Request  $request)
    {
        $agents =  Agent::where('status',1)->get();
        $q      = onlinePaymentLogs::orderBy('id','DESC');
        if($request->has('agent_id') && $request->agent_id){
            $q->where('agent_id',$request->agent_id);
        }
        if($request->has('start_date') && $request->start_date && $request->has('end_date') && $request->end_date){
            $from = Carbon::parse($request->start_date)->startOfDay();
            $to   = Carbon::parse($request->end_date)->endOfDay();
            $q->whereBetween('created_at',[$from,$to]);
            
            if($from->format('Y-m-d') <= '2023-03-31' ||
                $to->format('Y-m-d') <= '2023-03-31'
            ){
                $datas = collect([]);
                return view('accounts.online-transaction.index',compact('agents','datas'));
            }
        }

        $datas =   $q->simplePaginate(50);
        return view('accounts.online-transaction.index',compact('agents','datas'));
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
