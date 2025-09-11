<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct() {
        $this->middleware('permission:permission show', ['only' => ['index']]);
    }

    public function index()
    {
        $details = Permission::orderBy('id','DESC')->orderBy('id','DESC')->simplePaginate(50);
        return view('moderation.permissions.index',compact('details'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('moderation.permissions.create');
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
            'name' => 'required'
        ]);


        $details =[
            'name'=> $request->name
        ];

        $user = Permission::create($details);

        if($user)
            $request->session()->flash('success','Successfully Saved');
        else
            $request->session()->flash('error','Opps something went wrong');

        return redirect(route('permissions.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $details = Permission::find($id);
        return view('moderation.permissions.show',compact('details'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $details = Permission::find($id);
        $users = User::role(['manager','administrator','staff','b2c','accounts','marketing'])->get();
        $user_with_permission = User::permission($details)->get();
        return view('moderation.permissions.edit',compact('details','users','user_with_permission'));
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

        $this->validate($request,[
            'name' => 'required'
        ]);


        $details =[
            'name'=> $request->name
        ];

        $permission = Permission::where('id', $id)->update($details);

        if($permission)
            $request->session()->flash('success','Successfully Updated');
        else
            $request->session()->flash('error','Opps something went wrong');

        return redirect(route('permissions.index'));
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
