<?php

namespace App\Http\Controllers;

use App\Models\FlightTicket\Agent;
use App\Models\FlightTicket\DistributorAgentAlignment;
use Illuminate\Http\Request;

class DistributorAgentAlignmentController extends Controller
{
    public function index(Request  $request)
    {
        $distributor_id = $request->distributor_id;
        $agents = DistributorAgentAlignment::where('distributor_id',$distributor_id)->simplePaginate(50);
        $distributor = Agent::find($distributor_id);
        $distributorAgentAlignment = DistributorAgentAlignment::where('distributor_id',$distributor_id)->orderBy('id','DESC')->get();

        return view('settings.agents-distributors.distributor-agent-alignment',compact('agents','distributorAgentAlignment','distributor'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request  $request)
    {
        $distributor_id = $request->distributor_id;
        $agents = Agent::where('type',1)->select('phone','company_name','id','code')->get();
        $distributor = Agent::find($distributor_id);
        return view('settings.agents-distributors.distributor-agent-alignment-create',compact('agents','distributor'));
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
            'agent_id' => 'required|unique:distributor_agent_alignments'
        ]);
        $data = [
            'distributor_id' => $request->distributor_id,
            'agent_id' => $request->agent_id,
        ];

        $resp = DistributorAgentAlignment::create($data);
        $request->session()->flash('success','Successfully Saved');

        return redirect(route('agents-distributors-alignment.index',['distributor_id' => $resp->distributor_id]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DistributorAgentAlignment  $distributorAgentAlignment
     * @return \Illuminate\Http\Response
     */
    public function show(DistributorAgentAlignment $distributorAgentAlignment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DistributorAgentAlignment  $distributorAgentAlignment
     * @return \Illuminate\Http\Response
     */
    public function edit(DistributorAgentAlignment $distributorAgentAlignment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DistributorAgentAlignment  $distributorAgentAlignment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DistributorAgentAlignment $distributorAgentAlignment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DistributorAgentAlignment  $distributorAgentAlignment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $resp =  DistributorAgentAlignment::find($id);
        $resp->delete();
        return response()->json([
            'success' => true,
            'message' => 'Successfully Deleted'
        ]);
    }
}
