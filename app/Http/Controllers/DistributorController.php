<?php

namespace App\Http\Controllers;

use App\Models\FlightTicket\Agent;
use App\Models\FlightTicket\Airport;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DistributorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct() {
        $this->middleware('permission:distributor show', ['only' => ['index']]);
    }

    public function index(Request $request)
    {
        $agent = null;
        $agents = [];
        $staffs  = User::role('staff')->select('first_name', 'last_name', 'phone', 'id')->get();

        $airports = Airport::distinct('code')->where('countryName', 'INDIA')->select('id', 'name', 'code', 'cityName')->get();
        $q        = Agent::leftjoin('states','states.id','=','agents.state_id')
            ->leftjoin('users','users.id','=','agents.account_manager_id')
            ->leftjoin('airports','airports.id','=','agents.nearest_airport')
            ->orderBy('agents.created_at', 'DESC')->where('agents.type',2);

        if ($request->has('agency_id') && $request->agency_id != '') {
            $agent = Agent::find($request->agency_id);
            $q->where('agents.id', $request->agency_id);
        }

        if ($request->has('type') && $request->type != '') {
            $q->where('agents.type', $request->type);
        }

        if ($request->has('phone') && $request->phone != '') {
            $q->where('agents.phone', 'like', '%' . $request->phone . '%');
        }

        if ($request->has('email') && $request->email != '') {
            $q->where('agents.email', 'like', '%' . $request->email . '%');
        }

        if ($request->has('airport_id') && $request->airport_id != '') {
            $q->where('agents.nearest_airport', $request->airport_id);
        }

        if ($request->has('date_from') && $request->date_from != '' && $request->has('date_to') && $request->date_to != '') {
            $from = Carbon::parse($request->date_from);
            $to   = Carbon::parse($request->date_to);
            $q->whereBetween('agents.created_at', [$from, $to]);
        }

        if ($request->has('exclude_zero') && $request->exclude_zero != '') {
            //dd($request->has('exclude_zero'));
            $q->orWhere('agents.opening_balance', '!=', 0);
            $q->orWhere('agents.credit_balance', '!=', 0);
            $data = $q->select('agents.*','states.name as state_name','airports.cityCode as airport_city_code','users.first_name as account_manager_first_name','users.last_name as account_manager_last_name')
                ->simplePaginate(2000);
        } else {
            $data = $q->select('agents.*','states.name as state_name','airports.cityCode as airport_city_code','users.first_name as account_manager_first_name','users.last_name as account_manager_last_name')->simplePaginate(50);
        }


        return view('settings.distributors.index', compact('data', 'airports', 'agents', 'staffs', 'agent'));
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
