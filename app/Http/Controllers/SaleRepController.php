<?php

namespace App\Http\Controllers;

use App\Models\SaleHead;
use App\Models\SaleRep;
use App\Models\SaleRepAgentAlignment;
use App\Models\SaleTeamAlignment;
use App\User;
use Illuminate\Http\Request;

class SaleRepController extends Controller
{
    public function __construct() {
        $this->middleware('permission:sales_reps show', ['only' => ['index']]);
    }

    public function index(Request $request)
    {
        $q = SaleRep::orderBy('created_at', 'DESC');

        if($request->has('name') && $request->name != ''){
            $q->where('name','like','%'.strtolower($request->name).'%');
        }

        if($request->has('status') && $request->status != ''){
            $status = ($request->status == 2) ? 0 : $request->status;
            $q->where('status','=',$status);
        }

        $salesRep = $q->simplePaginate(50);

        return view('settings.sales-rep.index', compact('salesRep'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $salesHead = SaleHead::all();
        return view('settings.sales-rep.create', compact('salesHead'));
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email',
            'phone' => 'required|unique:users,phone',
            'password' => 'required',
            'balance' => 'required',
            'status' => 'required'
        ]);

        $resp = SaleRep::create([
            'name'      => $request->get('first_name') . ' '. $request->get('last_name'),
            'email'     => $request->get('email'),
            'password'  => $request->get('password'),
            'phone'     => $request->get('phone'),
            'balance'   => $request->get('balance'),
            'status'    => $request->get('status')
        ]);

        $login_data = [
            'first_name' =>  $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status' => $request->status
        ];

        if($resp) {
            SaleTeamAlignment::create([
                'sales_rep_id' => $resp->id,
                'sale_head_id' => $request->sales_head_id
            ]);

            $user = User::create($login_data);
            $user->assignRole('sales rep');
            $request->session()->flash('success', 'Successfully Saved');
        }else{
            $request->session()->flash('error', 'Opps something went wrong');
        }

        return redirect()->to('/settings/sales-rep');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SaleHead  $saleHead
     * @return \Illuminate\Http\Response
     */
    public function show(SaleRep $saleRep)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SaleHead  $saleHead
     * @return \Illuminate\Http\Response
     */
    public function edit(SaleRep $saleRep, $id)
    {
        $data = SaleRep::find($id);
        return view('settings.sales-rep.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SaleHead  $saleHead
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SaleRep $saleRep)
    {
        $salesHead = SaleRep::find($request->id);

        $salesHead->update([
            'name'       => $request->name,
            'password'   => $request->password,
            'balance'    => $request->balance,
            'status'     => $request->status
        ]);

        User::where('email', $request->email)->update([
            'password' => bcrypt($request->password),
            'status' => $request->status
        ]);

        $request->session()->flash('success', 'Successfully Updated');
        return redirect()->to('/settings/sales-rep');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SaleRep  $saleRep
     * @return \Illuminate\Http\Response
     */
    public function destroy(SaleRep $saleRep)
    {
        //
    }



    public function agentAlignment($id) {
        $salesRep = SaleRep::find($id);
        return view('settings.sales-rep.agent-alignment', compact('salesRep'));
    }



    public function agentAlignmentUpdate(Request $request) {
        $isExist = SaleRepAgentAlignment::where('agent_id', $request->agency_id)->count();
        if($isExist > 0) {
            $request->session()->flash('error', 'Agent is already assigned');
            return redirect()->to("/settings/sales-rep/agent-alignment/{$request->id}");
        }
        SaleRepAgentAlignment::create([
            'sales_rep_id' => $request->id,
            'agent_id'     => $request->agency_id
        ]);

        $request->session()->flash('success', 'Successfully Added');
        return redirect()->to("/settings/sales-rep/agent-alignment/{$request->id}");
    }




    public function agentAlignmentDelete(Request $request, $id, $alignment_id) {
        SaleRepAgentAlignment::destroy($alignment_id);
        $request->session()->flash('success', 'Successfully Deleted');
        return redirect()->to("/settings/sales-rep/agent-alignment/{$id}");
    }
}
