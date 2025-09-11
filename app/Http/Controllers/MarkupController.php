<?php

namespace App\Http\Controllers;

use App\Models\AgentMarkup;
use Illuminate\Http\Request;
use App\Models\FlightTicket\Agent;
use App\Models\FlightTicket\Airline;
use App\Models\FlightTicket\AirlineMarkup;
use App\Models\FlightTicketMarkupGlobalConfig;

class MarkupController extends Controller
{

    public function showMarkupPage(Request $request){

        $agent = null;
        $markups = AgentMarkup::with(['agent' => function($query) {
            $query->select('id','company_name','code');
        }])->simplePaginate(100);
        if ($request->has('agent_id') && $request->agent_id != '')
        {
            $agent = Agent::find($request->agent_id);
            $markups = AgentMarkup::with(['agent' => function($query) {
                $query->select('id','company_name','code');
            }])
            ->where('agent_id',$request->agent_id)->simplePaginate(100);

        }
        $global_markup_price = FlightTicketMarkupGlobalConfig::first();

        //return $global_markup_price;
        return view('settings.markup.markupconfig',compact('agent','markups','global_markup_price'));
    }

    public function __construct(){
        $this->middleware('permission:airline-markup show', ['only' => ['index']]);
        $this->middleware('permission:airline-markup create', ['only' => ['create,store']]);
        $this->middleware('permission:airline-markup update', ['only' => ['update']]);
    }

    public function index(Request $request)
    {
        $airlines = Airline::where('status',1)->pluck('name','id')->all();
        $agents = Agent::orderBy('company_name', 'ASC')->get();
        $q = AirlineMarkup::orderBy('id', 'DESC');

        if ($request->has('agent') && $request->agent) {
            $q->where('agent_id', $request->agent);
        }

        if ($request->has('flight') && $request->flight) {
            $q->where('airline_id', $request->flight);
        }

        if ($request->has('amounts') && $request->amounts) {
            $q->where('amount', $request->amounts);
        }

        if ($request->status != "") {
            $q->where('status', $request->status);
        }

        $airlineMarkups = $q->get();

        return view('settings.markup.airline-markup')
            ->with('airlines',$airlines)
            ->with('agents', $agents)
            ->with('airlineMarkups' , $airlineMarkups)->with('request', $request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $airlines = Airline::where('status',1)->pluck('name','id')->all();
        $agents = Agent::orderBy('company_name', 'ASC')->where('status',1)->get();

        return view('settings.markup.create')
            ->with('airlines',$airlines)
            ->with('agents', $agents);

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
            'agent' => 'required',
        ]);

        $agent_id = $request->get('agent');
        $airlines = $request->get('airlines');
        $amounts = $request->get('amounts');

        for($i = 0; $i < count($airlines); $i++) {
            if($amounts[$i]) {
                $airlineMarkup = new AirlineMarkup();
                $airlineMarkup->agent_id = $agent_id;
                $airlineMarkup->airline_id = $airlines[$i];
                $airlineMarkup->amount = $amounts[$i];
                $airlineMarkup->save();
            }
        }

        return redirect()->route('airline-markup.index')->with('success', 'Added Airline Markup Successfully');
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
    public function update(Request $request)
    {
        $id = $request->get('id');
        $amount = $request->get('value');

        $airlineMarkup = AirlineMarkup::find($id);
        $airlineMarkup->amount = $amount;
        $airlineMarkup->save();

        return response()->json([
            'success' => true
        ]);
    }




    public function updateStatus(Request $request)
    {
        $id = $request->get('id');
        $type = $request->get('value');

        if($type == 'activate') {
            $status = 1;
        }else{
            $status = 0;
        }

        $airlineMarkup = AirlineMarkup::find($id);
        $airlineMarkup->status = $status;
        $airlineMarkup->save();

        return response()->json([
            'success' => true
        ]);
    }



    public function updateIsActive($id, Request $request){
        $markups =AgentMarkup::find($id);
        $resp = $markups->update([
            'status' => $request->status
        ]);

        return response()->json([
            "succees" => true,
            "message" => 'Successfully Saved'
        ]);
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
