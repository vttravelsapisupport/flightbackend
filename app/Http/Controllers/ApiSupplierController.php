<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\State;
use App\PurchaseEntry;
use Illuminate\Http\Request;
use App\Models\FlightTicket\Owner;
use Illuminate\Support\Facades\DB;
use App\Models\FlightTicket\Airport;
use Illuminate\Support\Facades\Storage;
use App\Models\FlightTicket\ApiOwnersDetail;
use App\Models\FlightTicket\Accounts\SupplierBankDetails;

class ApiSupplierController extends Controller
{
    public function index(Request $request)
    {
        $owners = Owner::where('is_third_party', 2)->get();
        $q = Owner::orderBy('id','DESC');
        if ($request->has('owner_id') && $request->owner_id != '') {
            $q->where('id', $request->owner_id);
        }
        if ($request->has('mobile') && $request->mobile != '') {
            $q->where('mobile', 'like', '%' . $request->mobile . '%');
        }
        if ($request->has('email') && $request->email != '') {
            $q->where('email', 'like', '%' . $request->email . '%');
        }

        $q->where('is_third_party', 2);

        $data = $q->select('owners.*', DB::raw('(SELECT owner_balance from api_owners_details where supplier_id = owners.id) as owner_balance, (SELECT markup from api_owners_details where supplier_id = owners.id) as markup'))->simplePaginate(50);

        return view('settings.apiSupplier.index',compact('data','owners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $users =    User::role(['administrator', 'b2c', 'manager', 'marketing', 'staff', 'accounts'])->pluck('first_name', 'id')->all();
        $airports = Airport::distinct('code')->where('countryName', 'INDIA')->select('id', 'name', 'code', 'cityName')->get();
        $states =   State::distinct('state')->pluck('id', 'name')->all();

        return view('settings.apiSupplier.create_third_party', compact('states', 'airports', 'users'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if(isset($request->id)) {
            $owner = Owner::find($request->id);
        }else{
            $owner = new Owner;
        }

        DB::beginTransaction();
        try {
            $owner->name               = $request->contact_name;
            $owner->city               = $request->city;
            $owner->state_id           = $request->state;
            $owner->whatsapp           = $request->whatsapp;
            $owner->gst_no             = $request->gst_no;
            $owner->nearest_airport    = $request->airport;
            $owner->address            = $request->address;
            $owner->referred_by        = $request->referred_by;
            $owner->aadhaar_card_no    = $request->aadhaar_card_no;
            $owner->pan_card_no        = $request->pan_card_no;
            $owner->opening_balance    = $request->opening_balance;
            $owner->is_third_party     = 2;
            $owner->account_manager_id = $request->account_manager_id;
            $owner->isPANVerified      = ($request->has('isPANVerified')) ? 1 : 0;
            $owner->isGSTVerified      = ($request->has('isGSTVerified')) ? 1 : 0;
            $owner->isAadhaarVerified  = ($request->has('isAadhaarVerified')) ? 1 : 0;
            $owner->isEmailVerified    = ($request->has('isEmailVerified')) ? 1 : 0;
            $owner->isPhoneVerified    = ($request->has('isPhoneVerified')) ? 1 : 0;
            $owner->additional_email   = json_encode($request->additonal_email);

            $owner->status  = $request->status;
            $owner->mobile  = $request->phone;
            $owner->email   = $request->email;

            $additonal_contact_name = [];

            foreach($request->additional_contact['name'] as $key => $val){
                $temp_data = [
                    'name' => $request->additional_contact['name'][$key],
                    'phone' => $request->additional_contact['phone'][$key],
                    'whatsapp' => $request->additional_contact['whatsapp'][$key]
                ];
                array_push($additonal_contact_name,$temp_data);

            }
            $owner->additional_phone = json_encode($additonal_contact_name);

            // gst file upload
            if($request->has('gst_file')){
                $image_path = Storage::disk('s3')->put('agents',$request->gst_file);
                $owner->gst_url =  $image_path;
            }
            // pan file upload
            if($request->has('pan_file')){
                $image_path = Storage::disk('s3')->put('agents',$request->pan_file);
                $owner->pan_card_url =  $image_path;
            }
            // aadhaar card file upload
            if($request->has('aadhaar_card_file')){
                $image_path = Storage::disk('s3')->put('agents',$request->aadhaar_card_file);
                $owner->aadhaar_card_url =  $image_path;
            }


            $owner->save();
            $owner->refresh();

            if($owner->status == 2) { // Deactivated
                PurchaseEntry::where('owner_id' , $owner->id)->update(['isOnline' => 1]);
            }else{
                PurchaseEntry::where('owner_id' , $owner->id)->update(['isOnline' => 2]);
            }


            $credentials = [
                'user_name' => $request->user_name,
                'password'  => $request->user_password,
                'api_key'   => $request->api_key
            ];

            ApiOwnersDetail::updateOrCreate(
                ['supplier_id' => $owner->id],
                [
                    'supplier_id'   => $owner->id,
                    'owner_balance' => $request->owner_balance,
                    'credentials'   => json_encode($credentials),
                    'markup'        => $request->markup
                ]
            );


            if(!isset($request->id)) {
                DB::table('supplier_opening_balance_f_y_s')->insert([
                    'fys_id' => $this->getLatestFinancialYearID(),
                    'supplier_id' => $owner->id,
                    'amount' => 0,
                    'isActive' => 1
                ]);
            }

            DB::commit();

            $request->session()->flash('success', 'Successfully Update');
            return redirect(route('api-vendors.index'));
        } catch (\Exception $e) {
            print_r($e->getMessage());
            die;
            DB::rollback();
            report($e);
            $request->session()->flash('error', 'Oops something went wrong');
            return redirect()->back();
        }
    }




    public function getLatestFinancialYearID() {
        $financial_year = DB::table('f_y_s')->where('isActive', 1)->get();

        if(isset($financial_year[0])) {
            return $financial_year[0]->id;
        }

        return 0;
    }




    public function getSupplierID() {
        $role = DB::table('roles')->where('name', 'supplier')->get();

        if(isset($role[0])) {
            return $role[0]->id;
        }

        return 0;
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Owner  $owner
     * @return \Illuminate\Http\Response
     */
    public function show(Owner $owner)
    {
        $val =  $owner;
        $owner_bank_details = SupplierBankDetails::where('supplier_id',$owner->id)->get();
        return view('settings.apiSupplier.show',compact('val','owner_bank_details'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Owner  $owner
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Owner::find($id);
        $users = User::role(['administrator', 'b2c', 'manager', 'marketing', 'staff', 'accounts'])->pluck('first_name', 'id')->all();
        $airports = Airport::distinct('code')->where('countryName', 'INDIA')->select('id', 'name', 'code', 'cityName')->get();
        $states = State::distinct('state')->pluck('id', 'name')->all();
        $details =  ApiOwnersDetail::where('supplier_id', $id)->get();
        return view('settings.apiSupplier.create_third_party', compact('data','states', 'airports', 'users','details'));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Owner  $owner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Owner $owner)
    {
        $data = [
            'name' => $request->name
        ];

        $resp =  $owner->update($data);
        if($resp){
            $request->session()->flash('success','Successfully Updated');
        }
        else
            $request->session()->flash('error','Opps something went wrong');

        return redirect(route('vendors.index'));

    }



    public function getOwners(){
        $q = Owner::orderBy('id','DESC');
        $q->where('is_third_party', 2);

        $data = $q->select('owners.id', 'owners.name', 'owners.email', 'owners.mobile','owners.status', DB::raw('(SELECT credentials from api_owners_details where supplier_id = owners.id) as credentials'))->get();

        return $data;

    }

}
