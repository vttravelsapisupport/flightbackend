<?php

namespace App\Http\Controllers\FlightTicket\Accounts;

use App\User;
use Carbon\Carbon;
use App\Models\State;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Mail\AgentWelcomeMail;
use App\Models\FlightTicket\Agent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\FlightTicket\Airport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Models\FlightTicket\Accounts\AgentAccountType;


class AgentController extends Controller
{

    public function __construct() {
        $this->middleware('permission:agent show', ['only' => ['index']]);
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


        return view('settings.agents-distributors.index', compact('data', 'airports', 'agents', 'staffs', 'agent'));
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
        return view('settings.agents-distributors.create', compact('states', 'staff', 'account_type', 'airports'));
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
            'username' => 'required',
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
            'username' => $request->username,
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

        return redirect(route('agents-distributors.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function show(Agent $agent,$id)
    {
        abort_if(Gate::denies('agent show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $data = Agent::find($id);
        return view('settings.agents-distributors.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function edit(Agent $agent,$id)
    {
        $data = Agent::find($id);
        $users = User::role(['administrator', 'b2c', 'manager', 'marketing', 'staff', 'accounts'])->pluck('first_name', 'id')->all();
        $airports = Airport::distinct('code')->where('countryName', 'INDIA')->select('id', 'name', 'code', 'cityName')->get();
        $states = State::distinct('state')->pluck('id', 'name')->all();
        return view('settings.agents-distributors.edit', compact('data', 'states', 'airports', 'users'));
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
        $agent_data = [
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
            'additional_email' => json_encode($request->additonal_email),
            'credit_request_status' => $request->credit_request_status,
            'has_api' => $request->has_api,
            'account_type_id' => $request->account_type_id,
            'zipcode' => $request->zipcode,
        ];

        if ($agent->status != $request->status) {
            $user_details = User::where('phone', $agent->phone)->update([
                'status' => $request->status
            ]);
            $agent->update([
                'status' => $request->status
            ]);
        }
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
        if ($agent->credit_limit != $request->credit_limit) {
            if($agent->opening_balance >= 0) {
                $agent_data['credit_balance'] = $request->credit_limit;
                $agent_data['credit_limit']   = $request->credit_limit;
            }else{
                $agent_data['credit_balance'] = $agent->opening_balance + $request->credit_limit;
                $agent_data['credit_limit']   = $request->credit_limit;
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
            $agent_data['gst_url'] =  $image_path;
        }
        // pan file upload
        if($request->has('pan_file')){
            $image_path = Storage::disk('s3')->put('agents',$request->pan_file);
            $agent_data['pan_card_url'] =  $image_path;
        }
        // aadhaar card file upload
        if($request->has('aadhaar_card_file')){
            $image_path = Storage::disk('s3')->put('agents',$request->aadhaar_card_url);
            $agent_data['aadhaar_card_url'] =  $image_path;
        }
        try {
            $resp = $agent->update($agent_data);
            $request->session()->flash('success', 'Successfully Update');
            return redirect(route('agents-distributors.index'));
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
        return 'AID' . str_pad($datas->id + 1, 4, "0", STR_PAD_LEFT);
    }
    public function getDistributorCode(){
        $datas = Agent::orderBy('id', 'DESC')->where('type',2)->count();
        return 'DID' . str_pad($datas + 1, 4, "0", STR_PAD_LEFT);
    }

    public function generateAgentCode()
    {
        $datas = Agent::orderBy('id', 'ASC')->get();
        foreach ($datas as $key => $val) {
            $code = 'AID' . str_pad($val->id, 4, "0", STR_PAD_LEFT);
            $val->update([
                'code' => $code
            ]);
        }
    }
    public function sendEmail(Request $request, $agentId)
    {
        $agent = Agent::find($agentId);
        $user =  User::where('email', $agent->email)->first();
        dd($user);
        $password = Str::random(6);

        $user->update(['password' => bcrypt($password)]);
        $token =  Str::random(60);

        DB::table('password_resets')->insert([
            'email' => $agent->email,
            'token' => $token,
            'created_at' => \Carbon\Carbon::now()
        ]);
        Mail::to($agent->email)->send(new AgentWelcomeMail($agent, $user, $token, $password));
        $request->session()->flash('success', 'Successfully Saved');


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



}
