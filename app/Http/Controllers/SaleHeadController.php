<?php

namespace App\Http\Controllers;

use App\Models\SaleHead;
use App\User;
use Illuminate\Http\Request;

class SaleHeadController extends Controller
{

    public function __construct() {
        $this->middleware('permission:sales_reps show', ['only' => ['index']]);
    }
    
    public function index(Request $request)
    {
        $q = SaleHead::orderBy('created_at', 'DESC');

        if($request->has('name') && $request->name != ''){
            $q->where('name','like','%'.strtolower($request->name).'%');
        }

        if($request->has('status') && $request->status != ''){
            $status = ($request->status == 2) ? 0 : $request->status;
            $q->where('status','=',$status);
        }

        $salesHead = $q->simplePaginate(50);

        return view('settings.sales-head.index', compact('salesHead'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('settings.sales-head.create');
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

        $resp = SaleHead::create([
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
            $user = User::create($login_data);
            $user->assignRole('sales head');
            $request->session()->flash('success', 'Successfully Saved');
        }else{
            $request->session()->flash('error', 'Oops something went wrong');
        }

        return redirect()->to('/settings/sales-head');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SaleHead  $saleHead
     * @return \Illuminate\Http\Response
     */
    public function show(SaleHead $saleHead)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SaleHead  $saleHead
     * @return \Illuminate\Http\Response
     */
    public function edit(SaleHead $saleHead, $id)
    {
        $data = SaleHead::find($id);
        return view('settings.sales-head.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SaleHead  $saleHead
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SaleHead $saleHead)
    {
        $salesHead = SaleHead::find($request->id);

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
        return redirect()->to('/settings/sales-head');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SaleHead  $saleHead
     * @return \Illuminate\Http\Response
     */
    public function destroy(SaleHead $saleHead)
    {
        //
    }
}
