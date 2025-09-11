<?php

namespace App\Http\Controllers;

use App\User;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use App\Models\State;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\AgentWelcomeMail;
use App\Enums\CustomerTypeEnum;
use App\Models\AgentFollowRemark;
use OwenIt\Auditing\Models\Audit;
use App\Models\FlightTicket\Agent;
use Illuminate\Support\Facades\DB;
use App\Models\FlightTicket\Airline;
use App\Models\FlightTicket\Airport;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\FlightTicket\BookTicket;
use Illuminate\Support\Facades\Storage;
use App\Models\FlightTicket\AirlineMarkup;
use Symfony\Component\HttpFoundation\Response;
use App\Models\FlightTicket\Accounts\AgentAccountType;
use App\Models\FlightTicket\Accounts\AgentDebitorRemark;


class AgentController extends Controller
{

    public function __construct() {
        $this->middleware('permission:agent show', ['only' => ['index']]);
    }

    public function remarks(Request $request){
        $data = [];
        $agents = [];
        $limit = 50;

        if($request->has('results') && $request->results != ''){
            $limit = $request->result;
        }

        if($request->has('agent_id')){
            $agent_id = $request->agent_id;
            $agents = Agent::find($agent_id);
            $data = AgentDebitorRemark::with('user')->where([
                'agent_id' => $agent_id
            ])->orderBy('id', 'DESC')->simplePaginate( $limit );
        }

        return view('settings.customers.remarks',compact('data','agents'));
    }

    public function createRemarks(Request $request){

        $agents = [];


        if($request->has('agent_id')){
            $agent_id = $request->agent_id;
            $agents = Agent::find($agent_id);
        }


        return view('settings.customers.remarks_create',compact('agents'));
    }

    public function storeRemarks(Request $request){
        $agent_id = $request->agent_id;
        $remarks = $request->remarks;

        $user_id = Auth::id();


        $resp = AgentDebitorRemark::create([
            'agent_id' => $agent_id,
            'remarks' => $remarks,
            'owner_id' => $user_id
        ]);

        $request->session()->flash('success','Successfully Saved');
        return redirect(route('debitor-remarks',['agent_id'=> $request->agent_id]));
    }

    public function index(Request  $request)
    {
        $agent = null;
        $agents = [];
        $staffs  = User::role('staff')->select('first_name', 'last_name', 'phone', 'id')->get();

        $airports = Airport::distinct('code')->where('countryName', 'INDIA')->select('id', 'name', 'code', 'cityName')->get();
        $q        = Agent::leftjoin('states','states.id','=','agents.state_id')
            ->leftjoin('users','users.id','=','agents.account_manager_id')
            ->leftjoin('airports','airports.id','=','agents.nearest_airport')
            ->orderBy('agents.created_at', 'DESC');

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


        return view('settings.customers.index', compact('data', 'airports', 'agents', 'staffs', 'agent'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $states = State::distinct('state')->pluck('id', 'name')->all();
        $staff  = User::role('staff')->select('first_name', 'last_name', 'phone', 'id')->get();
        $account_type = AgentAccountType::pluck('id', 'alias')->all();
        $airports = Airport::distinct('code')->where('countryName', 'INDIA')->select('id', 'name', 'code', 'cityName')->get();
        return view('settings.customers.create', compact('states', 'staff', 'account_type', 'airports'));
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
            'type' => 'required',
            'company_name' => 'required',
            'contact_name' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'email' => 'required|unique:users,email',
            'phone' => 'required|unique:users,phone',
            'password' => 'required',
            'airport' => 'required',
            'zipcode' => 'required'
        ]);
        $data = [
            'type' => $request->type,
            'alias' => $request->company_name,
            'company_name' => $request->company_name,
            'contact_name' => $request->contact_name,
            'city' => $request->city,
            'state_id' => $request->state,
            'country' => $request->country,
            'email' => $request->email,
            'phone' => $request->phone,
            'whatsapp' => $request->whatsapp,
            'gst_no' => $request->gst_no,
            'account_manager_id' => $request->account_manager_id,
            'credit_agent' => $request->credit_agent,
            'account_type_id' => $request->account_type_id,
            'username' => $request->phone,
            'password' => $request->password,
            'nearest_airport' => $request->airport,
            'address' => $request->address,
            'aadhaar_card_no' => $request->aadhaar_no,
            'pan_card_no' => $request->pan_no,
            'zipcode' => $request->zipcode,
            'code' => ($request->type == 1) ? $this->getAgentCode() : $this->getDistributorCode(),
        ];

        $contact_name_array = explode(' ', $request->contact_name);

        $login_data = [
            'first_name' => $contact_name_array[0],
            'last_name' => $contact_name_array[1],
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status' => 1
        ];


        $resp = Agent::create($data);

        if ($resp) {
            $user = User::create($login_data);
            if($request->type == 1) {
                $user->assignRole('agent');
            }
            elseif($request->type == 2){
                $user->assignRole('distributor');
            }
            //send email
            //Mail::to($user->email)->send(new AgentsRegistration($resp,$user,$request->password));
            $request->session()->flash('success', 'Successfully Saved');
        } else
            $request->session()->flash('error', 'Opps something went wrong');

        return redirect(route('agents.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort_if(Gate::denies('agent show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $data = Agent::find($id);
        $history = Audit::where('auditable_id',$id)->whereIn('auditable_type',['App\Models\FlightTicket\Agent'])->orderBy('id','DESC')->get();


        return view('settings.customers.show', compact('data','history'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Agent::find($id);
        $users = User::role(['administrator', 'b2c', 'manager', 'marketing', 'staff', 'accounts'])->pluck('first_name', 'id')->all();
        $airports = Airport::distinct('code')->where('countryName', 'INDIA')->select('id', 'name', 'code', 'cityName')->get();
        $states = State::distinct('state')->pluck('id', 'name')->all();
        return view('settings.customers.edit', compact('data', 'states', 'airports', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // return $request->all();

        $agent = Agent::find($id);

        $user  = Auth::user();

        $agent_data = [
            'type'=> $request->type,
            'alias' => $request->company_name,
            'company_name' => $request->company_name,
            'contact_name' => $request->contact_name,
            'city' => $request->city,
            'state_id' => $request->state,
            'country' => $request->country,
            'whatsapp' => $request->whatsapp,
            'gst_no' => $request->gst_no,
            'nearest_airport' => $request->airport,
            'address' => $request->address,
            'referred_by' => $request->referred_by,
            'status' => $request->status,
            'aadhaar_card_no' => $request->aadhaar_card_no,
            'pan_card_no' => $request->pan_card_no,
            'opening_balance' => $request->opening_balance,
            'credit_balance' => $request->credit_balance,
            'account_manager_id' => $request->account_manager_id,
            'isPANVerified' => ($request->has('isPANVerified')) ? 1 : 0,
            'isGSTVerified' => ($request->has('isGSTVerified')) ? 1 : 0,
            'isAadhaarVerified' => ($request->has('isAadhaarVerified')) ? 1 : 0,
            'isEmailVerified' => ($request->has('isEmailVerified')) ? 1 : 0,
            'isPhoneVerified' => ($request->has('isPhoneVerified')) ? 1 : 0,
            'additional_email' => json_encode($request->additonal_email),
            'credit_request_status' => $request->credit_request_status,
            'has_api' => $request->has_api,
            'account_type_id' => $request->account_type_id,
            'zipcode' => $request->zipcode,
            'remarks' => $request->remarks,
        ];


        $status = ["0","1"];
        $active_status = ["1"];
        $inactive_status = ["0","2","3","4"];

        if ($agent->status != $request->status ) {
            if(in_array($request->status, $inactive_status)){
                $user_details = User::where('phone', $agent->phone)->update([
                    'status' => 0
                ]);
                $agent->update([
                    'status' => $request->status
                ]);
            }elseif(in_array($request->status, $active_status)){
                $user_details = User::where('phone', $agent->phone)->update([
                    'status' => 1
                ]);
                $agent->update([
                    'status' => $request->status
                ]);
            }

        }

        // if ($agent->type != $request->type ) {
        //     $customerUser = User::where('email', $agent->email)->first();
            
        //     if (!$customerUser) {
        //         throw new \Exception("User not found.");
        //     }
        
        //     $role = Role::findOrFail($request->role_id);
        //     $current_role = $customerUser->getRoleNames()->first();

        //     if ($current_role && $current_role !== $role->name) {
        //         $customerUser->removeRole($current_role);
        //     }
        
        //     if (!$current_role || $current_role !== $role->name) {
        //         $customerUser->assignRole($role->name);
        //     }
        // }


        if ($agent->phone != $request->phone) {
            $agent_data['phone'] = $request->phone;
            try{
                $user_details = User::where('phone', $agent->phone)->update([
                    'phone' => $request->phone
                ]);
            }
            catch(\Exception $e){
                $request->session()->flash('error', 'This phone no. is already taken');
                return redirect()->back();
            }
        }
        if ($agent->email != $request->email) {
            $agent_data['email'] = $request->email;
            $user_data['email'] = $request->email;

            try{
                $user_details = User::where('email', $agent->email)->update([
                    'email' => $request->email
                ]);
            }catch(\Exception $e){
                $request->session()->flash('error', 'This email is already taken');
                return redirect()->back();
            }
        }

        // Update credit limit for agent
        if($user->can('credit_limit update')) {
            if ($agent->credit_limit != $request->credit_limit) {
                if($agent->opening_balance >= 0) {
                    $agent_data['credit_balance'] = $request->credit_limit;
                    $agent_data['credit_limit']   = $request->credit_limit;
                }else{
                    $agent_data['credit_balance'] = $agent->opening_balance + $request->credit_limit;
                    $agent_data['credit_limit']   = $request->credit_limit;
                }
            }
        }

        $additonal_contact_name = [];

        foreach($request->additional_contact['name'] as $key => $val){
            $temp_data = [
                'name' => $request->additional_contact['name'][$key],
                'phone' => $request->additional_contact['phone'][$key],
                'whatsapp' => $request->additional_contact['whatsapp'][$key]
            ];
            array_push($additonal_contact_name,$temp_data);
        }

        $agent_data['additional_phone'] = json_encode($additonal_contact_name);

        // gst file upload
        if($request->has('gst_file')){
            $image_path = Storage::disk('s3')->put('agents',$request->gst_file);
        }
        // pan file upload
        if($request->has('pan_file')){
            $image_path = Storage::disk('s3')->put('agents',$request->pan_file);
            $agent_data['pan_card_url'] =  $image_path;
        }
        // aadhaar card file upload
        if($request->has('aadhaar_card_file')){
            $image_path = Storage::disk('s3')->put('agents',$request->aadhaar_card_file);
            $agent_data['aadhaar_card_url'] =  $image_path;
        }



        //Auto Add makup if API Agent
        if($request->has_api) {
            $agent_airline_markup = AirlineMarkup::where('agent_id', $id)->get();
            if(!isset($agent_airline_markup[0])) {
                $airlines = Airline::all();
                foreach($airlines as $airline) {
                    AirlineMarkup::create([
                        'airline_id' => $airline->id,
                        'agent_id' => $id,
                        'amount' => 50,
                        'status' => 1
                    ]);
                }
            }
        }



        try {
            $agent->update($agent_data);
            $request->session()->flash('success', 'Successfully Update');
            return redirect(route('agents.index'));
        } catch (Throwable $e) {
            report($e);
            $request->session()->flash('error', 'Oops something went wrong');
            return false;
        }

    }



    public function recalculationBalance($agent, $request) {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Agent  $agent
     * @return \Illuminate\Http\Response
     */

    public function getAgentCode()
    {
        $datas = Agent::orderBy('id', 'DESC')->where('type',1)->first();
        if($datas){
            return 'VID' . str_pad($datas->id + 1, 4, "0", STR_PAD_LEFT);
        }
        return 'VID' . str_pad(1 + 1, 4, "0", STR_PAD_LEFT);

    }
    public function getDistributorCode(){
        $datas = Agent::orderBy('id', 'DESC')->where('type',2)->count();
        return 'DID' . str_pad($datas + 1, 4, "0", STR_PAD_LEFT);
    }

    public function generateAgentCode()
    {
        $datas = Agent::orderBy('id', 'ASC')->get();
        foreach ($datas as $key => $val) {
            $code = 'VID' . str_pad($val->id, 4, "0", STR_PAD_LEFT);
            $val->update([
                'code' => $code
            ]);
        }
    }
    public function sendEmail(Request $request, $agentId)
    {
        $agent = Agent::find($agentId);
        $user =  User::where('email', $agent->email)->first();

        $password = Str::random(6);
        $user->update(['password' => bcrypt($password)]);
        $token =  Str::random(60);
        DB::beginTransaction();
        try {
            DB::table('password_resets')->insert([
                'email' => $agent->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
            Mail::to($agent->email)->send(new AgentWelcomeMail($agent, $user, $token, $password));
            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollback();
            $request->session()->flash('error',$e->getMessage());
            return redirect()->back();
        }
        $request->session()->flash('success', 'Successfully send a welcome email at '.$agent->email );
        return back();
    }

    public function destroy(Request $request, Agent $agent)
    {

        if ($agent->getSaleTicket->count() == 0 && $agent->getAccountTransaction->count() == 0) {
            // delete the agent from agent and user table
            $agentResp = $agent->delete();
            $user = User::where('email', $agent->email)->delete();
            $request->session()->flash('success', 'Successfully Deleted');
            return redirect()->back();
        } else {
            $request->session()->flash('error', 'Agent can\'t be deleted');
            return redirect()->back();
        }



        // check in sale ticket
        // check in account transaction

    }




    public function getAllActiveAgents()
    {
        $agents = Agent::with(['tickets' => function ($query) {
        }])
            ->select('id', 'code', 'company_name', 'city', 'state_id', 'opening_balance')
            ->where('status', 1)
            ->get();
        $activeAgents = [];
        foreach ($agents as $agent) {
            if ($agent->tickets) {
                array_push($activeAgents, $agent);
            }
        }


        return response()->json($activeAgents);
    }




    public function agentTypeReport(Request $request) {

        $type = $request->get('type');
        $agents = [];

        if($type == 1) {
            $q = Agent::where('code', '!=', null);
        }

        if($type == 2) {
            $q = Agent::where('status', '=', 1);
        }

        if($type == 3) {
            $q = Agent::where('status', '!=', 1);
        }

        if($type == 4) {
            $bookingAgents      = BookTicket::distinct()->get(['agent_id']);
            $bookingAgentsIds   = [];
            foreach($bookingAgents as $value) {
                array_push($bookingAgentsIds, $value->agent_id);
            }
            $q = Agent::whereIn('id' , $bookingAgents);
        }

        if($type == 5) {
            $activeAgents    = Agent::where('status', '=', 1)->pluck('id');
            $activeAgentsIds   = [];
            foreach($activeAgents as $value) {
                array_push($activeAgentsIds, $value);
            }

            $bookingAgents      = BookTicket::distinct()->get(['agent_id']);
            $bookingAgentsIds   = [];
            foreach($bookingAgents as $value) {
                array_push($bookingAgentsIds, $value->agent_id);
            }

            $nonTransactingAgentIds  = array_diff ( $activeAgentsIds , $bookingAgentsIds);
            $q = Agent::whereIn('id' , $nonTransactingAgentIds);
        }

        if($type == 6) {
            $start_date = Carbon::parse($request->start_date)->startOfDay();
            $end_date = Carbon::parse($request->end_date)->endOfDay();

            $bookingAgents      = BookTicket::whereBetween('created_at',[$start_date,$end_date])->distinct()->get(['agent_id']);
            $bookingAgentsIds   = [];
            foreach($bookingAgents as $value) {
                array_push($bookingAgentsIds, $value->agent_id);
            }
            $q = Agent::whereIn('id' , $bookingAgents);
        }

        if($type == 7) {
            $bookingAgents      = BookTicket::distinct()->get(['agent_id']);
            $bookingAgentsIds   = [];
            foreach($bookingAgents as $value) {
                array_push($bookingAgentsIds, $value->agent_id);
            }

            $start_date = Carbon::parse($request->start_date)->startOfDay();
            $end_date = Carbon::parse($request->end_date)->endOfDay();

            $bookingTransatingAgents   = BookTicket::whereBetween('created_at',[$start_date,$end_date])->distinct()->get(['agent_id']);
            $bookingTransatingAgentsIds   = [];
            foreach($bookingTransatingAgents as $value) {
                array_push($bookingTransatingAgentsIds, $value->agent_id);
            }

            $nonTransactingAgentIds  = array_diff ( $bookingAgentsIds , $bookingTransatingAgentsIds);
            $q = Agent::whereIn('id' , $nonTransactingAgentIds);
        }

        if($type == 8) {
            $q = Agent::where('status', '=', 2);
        }

        if($type == 9) {
            $q = Agent::where('status', '=', 3);
        }

        if($type == 10) {
            $q = Agent::where('status', '=', 4);
        }

        if($request->has('agent_id') && $request->agent_id != ''){
            $q->where('agent_id', $request->agent_id);
        }

        $datas = $q->simplePaginate(50);

        return view('settings.customers.agent_list', compact('agents','datas'));

    }


    public function updateAgentsRemark(Request $request) {
        $user  = Auth::user();
        AgentFollowRemark::create([
            'agent_id'  => $request->agent_id,
            'user_id'   => $user->id,
            'remarks'   => $request->remarks
        ]);
        $request->session()->flash('success','Successfully Updated');
        return redirect()->back();
    }

    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required'
        ]);

        $agent = Agent::findorFail($id);
        $user = User::where('email', $agent->email)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        if (!Hash::check($request->old_password, $user->password)) {
            return redirect()->back()->withErrors(['old_password' => 'Old password does not match.']);
        }

        $user->password = bcrypt($request->password);
        $user->save();
    
        return redirect()->back()->with('success', 'Password reset successfully.');
    }

}
