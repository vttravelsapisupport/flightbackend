<?php

namespace App\Http\Controllers;

use App\Mail\NewUser;
use App\Models\LoginActivity;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function __construct() {
        $this->middleware('permission:users show', ['only' => ['index']]);
    }

    public function index(Request $request){
        $roles = Role::pluck('id','name')->all();

        $q     = User::orderBy('id','DESC');

        if($request->has('name') && $request->name != '' )
            $q->where('first_name','like','%'.$request->name.'%');
        if($request->has('email') && $request->email != '' )
            $q->where('email','like','%'.$request->email.'%');
        if($request->has('phone') && $request->phone != '' )
            $q->where('phone','like','%'.$request->phone.'%');
        if($request->has('role_id') && $request->role_id != '' )
            $q->role($request->role_id);

        $details =$q->simplePaginate(50);

        return view('moderation.users.index',compact('roles','details'));
    }

    public function create(){
        $roles = Role::pluck('name','id')->all();
        return view('moderation.users.create',compact('roles'));
    }

    public function show($id){
        $roles = Role::pluck('id','name')->all();
        $details = User::find($id);
        $user_activity = LoginActivity::where('user_id',$id)->orderBy('id','DESC')->simplePaginate(50);
        return view('moderation.users.show',compact('roles','details','user_activity'));
    }

    public function edit($id) {
        $details = User::find($id);
        $roles = Role::pluck('name','id')->all();
        return view('moderation.users.edit',compact('details','roles'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|digits:10|unique:users',
            'password' => 'required|min:6|max:16',
            'role_id' => 'required|integer',

        ]);

        $role = Role::findOrFail($request->role_id);

        $password = $request->password;

        $details =[
            'first_name'=> $request->first_name,
            'last_name' =>  $request->last_name,
            'email'     =>  $request->email,
            'phone'     =>  $request->phone,
            'password'  =>  bcrypt($password),
        ];

        $user = User::create($details);
        $user->assignRole($role->name);

        try {
            Mail::to($request->email)->send(new NewUser($user,$password));
        } catch (\Exception $e) {
            Log::error($e);
        }

        if($user)
            $request->session()->flash('success','Successfully Saved');
        else
            $request->session()->flash('error','Opps something went wrong');

        return redirect(route('users.index'));
    }

    public function update(Request $request, $id)
    {

        $user = User::find($id);

        $this->validate($request,[
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,'. $id,
            'phone' => 'required|digits:10|unique:users,phone,'. $id,
            'role_id' => 'required|integer',
            'status' => 'required|integer',
        ]);

        $role = Role::findOrFail($request->role_id);


        $details =[
            'first_name'=> $request->first_name,
            'last_name' =>  $request->last_name,
            'email'     =>  $request->email,
            'phone'     =>  $request->phone,
            'status'     =>  $request->status,
        ];

        $resp = $user->update($details);


        $current_role =  head($user->getRoleNames()->toArray());

        if($current_role !== $role->name){
            $user->removeRole($current_role);
            $user->assignRole($role->name);
        }


        if($resp)
            $request->session()->flash('success','Successfully Updated');
        else
            $request->session()->flash('error','Opps something went wrong');

        return redirect(route('users.index'));
    }

    public function update_password(Request  $request){

        $user_id = $request->id;
        $new_password = bcrypt($request->password);

        $validator = \Validator::make($request->all(), [
            'password' => 'required|confirmed|min:6|max:16',
            'id' => 'required|integer',
        ]);
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        User::where('id',$user_id)->update([
                'password' => $new_password
        ]);
        $request->session()->flash('success','Successfully Changed Password');
        return redirect()->back();
    }
}
