<?php

namespace App\Http\Controllers;

use App\Models\FlightTicket\Agent;
use App\Models\FlightTicket\AgentSupplierRestriction;
use App\Models\FlightTicket\Owner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentSupplierRestrictionController extends Controller
{

    public function __construct() {
        $this->middleware('permission:agent_supplier_restriction show', ['only' => ['index']]);
    }

    public function index(Request $request){
//         $q = AgentSupplierRestriction::orderBy('id','DESC');
//         if($request->has('agent_id') and $request->agent_id != '') {
//             $agent = Agent::find($request->agent_id);
//             $q->where('agent_id', $request->agent_id);
//         } else {
//             $agent = '';
//         }

        $q = Agent::orderBy('code','asc');
        if($request->has('agent_id') and $request->agent_id != '') {
            $q->where('id', $request->agent_id);
            $agent = Agent::find($request->agent_id);
        } else {
            $agent = '';
        }

        $data =  $q->simplePaginate(50);
        return view('settings.agentSupplierRestriction.index',compact('data','agent'));
    }

    public function show($id,Request $request){
        $q = AgentSupplierRestriction::orderby('id','DESC')->where('agent_id',$id);
        $data =  $q->simplePaginate(50);
        $agentDetails = Agent::find($id);
        return view('settings.agentSupplierRestriction.show',compact('data','id','agentDetails'));
    }


    public function create(Request $request){
        $agent = $request->agent_id;
        $agentDetails = Agent::find($agent);
        $restriction = AgentSupplierRestriction::where('agent_id',$agent)->pluck('supplier_id')->all();

        $suppliers = Owner::whereNotIn('id',$restriction)->pluck('name','id')->all();

        return view('settings.agentSupplierRestriction.create',compact('agent','agentDetails','suppliers'));
    }

    public function edit($id,Request $request){
        return view('settings.agentSupplierRestriction.edit');
    }

    public function update($id,Request $request){

    }

    public function store(Request $request){
        $this->validate($request,[
            'agent_id' => 'required',
            'supplier_id' => 'required'
        ]);

        foreach($request->supplier_id as $val) {
            AgentSupplierRestriction::Create([
                'agent_id' => $request->agent_id,
                'supplier_id' => $val,
                'user_id' => Auth::id(),
                'status' => 1,
            ]);
        }

        $request->session()->flash('success','Successfully Saved');

        return redirect(url('/settings/agent-supplier-restrictions/'.$request->agent_id));

    }

    public function destroy($id,Request $request){

        $agent = AgentSupplierRestriction::find($id);
        $resp  = $agent->delete();

        $request->session()->flash('success','Successfully Deleted !');

        return redirect(url('/settings/agent-supplier-restrictions/'.$request->agent_id));


    }
}
