<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct() {
        $this->middleware('permission:role show', ['only' => ['index']]);
    }

    public function index()
    {
        $details = Role::orderBy('id','DESC')->simplePaginate(50);
        return view('moderation.roles.index',compact('details'));
    }

    /**
     * Show the form for creatinUg a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::orderBy('id','DESC')->get();
        return view('moderation.roles.create',compact('permissions'));
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
            'name' => 'required',
            'permissions' => 'required|array'
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        if($role)
            $request->session()->flash('success','Successfully Saved ');
        else
            $request->session()->flash('error','Opps something went wrong');

        return redirect(route('roles.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $details = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
                ->where("role_has_permissions.role_id",$id)
                ->get();
        return view('moderation.roles.show',compact('details','rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $details = Role::find($id);
        $permissions = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")
                            ->join('permissions','permissions.id','=','role_has_permissions.permission_id')
                            ->where("role_has_permissions.role_id",$id)
                            ->pluck('permissions.name','permissions.name')
                            ->all();

        return view('moderation.roles.edit',compact('details','permissions','rolePermissions'));
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
        $this->validate($request, [
            'name' => 'required',
            'permissions' => 'required',
        ]);


        $role = Role::find($id);
        $role->name = $request->name;
        $role->save();
        if($role->name !== $request->name){
            echo  $role->name;
            echo  $role->name;


        }

        $role->syncPermissions($request->permissions);
        if($role)
            $request->session()->flash('success','Successfully Saved ');
        else
            $request->session()->flash('error','Opps something went wrong');

        return redirect(route('roles.index'));

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
