<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\State;
use App\PurchaseEntry;
use Illuminate\Http\Request;
use App\Models\BookingRequestLog;
use App\Models\FlightTicket\Agent;
use App\Models\FlightTicket\Owner;
use Illuminate\Support\Facades\DB;
use App\Models\FlightTicket\Airport;
use App\Models\V1APIBookingRequestLog;
use App\Models\V2APIBookingRequestLog;
use Illuminate\Support\Facades\Storage;
use App\Models\FlightTicket\Accounts\SupplierBankDetails;
use App\Models\FlightTicket\Accounts\SupplierOpeningBalanceFY;

class OwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:owner show', ['only' => ['index']]);
    }

    public function index(Request $request)
    {
        $owners = Owner::get();
        $q = Owner::orderBy('id', 'DESC');
        if ($request->has('owner_id') && $request->owner_id != '') {
            $q->where('id', $request->owner_id);
        }
        if ($request->has('mobile') && $request->mobile != '') {
            $q->where('mobile', 'like', '%' . $request->mobile . '%');
        }
        if ($request->has('email') && $request->email != '') {
            $q->where('email', 'like', '%' . $request->email . '%');
        }

        $details = $q->where('is_third_party', '!=', 2)->simplePaginate(50);

        return view('settings.vendors.index', compact('details', 'owners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $users = User::role(['administrator', 'b2c', 'manager', 'marketing', 'staff', 'accounts'])->pluck('first_name', 'id')->all();
        $airports = Airport::distinct('code')->where('countryName', 'INDIA')->select('id', 'name', 'code', 'cityName')->get();
        $states = State::distinct('state')->pluck('id', 'name')->all();
        return view('settings.vendors.create_third_party', compact('states', 'airports', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (isset($request->id)) {
            $owner = Owner::find($request->id);
        } else {
            $this->validate(
                $request,
                [
                    'email' => 'required|unique:users|email',
                    'phone' => 'required|unique:users',
                ]
            );
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
            $owner->is_third_party     = ($request->has('is_third_party')) ? 1 : 0;
            $owner->account_manager_id = $request->account_manager_id;
            $owner->isPANVerified      = ($request->has('isPANVerified')) ? 1 : 0;
            $owner->isGSTVerified      = ($request->has('isGSTVerified')) ? 1 : 0;
            $owner->isAadhaarVerified  = ($request->has('isAadhaarVerified')) ? 1 : 0;
            $owner->isEmailVerified    = ($request->has('isEmailVerified')) ? 1 : 0;
            $owner->isPhoneVerified    = ($request->has('isPhoneVerified')) ? 1 : 0;
            $owner->additional_email   = json_encode($request->additonal_email);


            if ($request->id) {
                if (User::where('phone', $owner->mobile)->count() ==  0) {
                    $user             = new User;
                    $user->first_name = $request->contact_name;
                    $user->last_name  = "";
                    $user->email      = $request->email;
                    $user->phone      = $request->phone;
                    $user->status     = $request->status;
                    $user->password   = bcrypt($request->password);
                    $user->save();
                    $user->refresh();

                    DB::table('model_has_roles')->insert(['role_id' => $this->getSupplierID(), 'model_type' => 'App\User', 'model_id' => $user->id]);
                    DB::table('supplier_opening_balance_f_y_s')->insert([
                        'fys_id' => $this->getLatestFinancialYearID(),
                        'supplier_id' => $owner->id,
                        'amount' => 0,
                        'isActive' => 1
                    ]);
                } else {
                    if ($owner->email != $request->email) {
                        User::where('email', $owner->email)->update([
                            'email' => $request->email
                        ]);
                    }

                    if ($owner->mobile != $request->phone) {
                        User::where('phone', $owner->mobile)->update([
                            'phone' => $request->phone
                        ]);
                    }

                    if ($owner->status != $request->status) {
                        $status = $request->status;
                        if ($status == 2) {
                            $status = 0;
                        }
                        User::where('id', $owner->id)->update([
                            'status' => $status
                        ]);
                    }

                    if ($request->has('password') &&  $request->password != '') {
                        User::where('id', $owner->id)->update([
                            'password' => bcrypt($request->password)
                        ]);
                    }
                }
            } else {
                $user = new User;
                $user->first_name = $request->contact_name;
                $user->last_name = "";
                $user->email = $request->email;
                $user->phone = $request->phone;
                $user->status = $request->status;
                $user->password = bcrypt($request->password);
                $user->save();
                $user->refresh();
            }

            $owner->status  = $request->status;
            $owner->mobile  = $request->phone;
            $owner->email   = $request->email;

            $additonal_contact_name = [];

            foreach ($request->additional_contact['name'] as $key => $val) {
                $temp_data = [
                    'name' => $request->additional_contact['name'][$key],
                    'phone' => $request->additional_contact['phone'][$key],
                    'whatsapp' => $request->additional_contact['whatsapp'][$key]
                ];
                array_push($additonal_contact_name, $temp_data);
            }
            $owner->additional_phone = json_encode($additonal_contact_name);

            // gst file upload
            if ($request->has('gst_file')) {
                $image_path = Storage::disk('s3')->put('vendors',$request->gst_file);
                $owner->gst_url =  $image_path;
            }
            // pan file upload
            if ($request->has('pan_file')) {
                $image_path = Storage::disk('s3')->put('vendors',$request->pan_file);
                $owner->pan_card_url =  $image_path;
            }
            // aadhaar card file upload
            if ($request->has('aadhaar_card_file')) {
                $image_path = Storage::disk('s3')->put('vendors',$request->aadhaar_card_file);
                $owner->aadhaar_card_url =  $image_path;
            }


            $owner->save();
            $owner->refresh();

            if ($owner->status == 2) { // Deactivated
                PurchaseEntry::where('owner_id', $owner->id)->update(['isOnline' => 1]);
            } else {
                PurchaseEntry::where('owner_id', $owner->id)->update(['isOnline' => 2]);
            }


            if (!isset($request->id)) {
                DB::table('model_has_roles')->insert(['role_id' => $this->getSupplierID(), 'model_type' => 'App\User', 'model_id' => $user->id]);
                DB::table('supplier_opening_balance_f_y_s')->insert([
                    'fys_id' => $this->getLatestFinancialYearID(),
                    'supplier_id' => $owner->id,
                    'amount' => 0,
                    'isActive' => 1
                ]);
            }

            DB::commit();

            $request->session()->flash('success', 'Successfully Update');
            return redirect(route('vendors.index'));
        } catch (\Exception $e) {
            print_r($e->getMessage());
            die;
            DB::rollback();
            report($e);
            $request->session()->flash('error', 'Oops something went wrong');
            return redirect()->back();
        }
    }



    public function getLatestFinancialYearID()
    {
        $financial_year = DB::table('f_y_s')->where('isActive', 1)->get();

        if (isset($financial_year[0])) {
            return $financial_year[0]->id;
        }

        return 0;
    }


    public function getSupplierID()
    {
        $role = DB::table('roles')->where('name', 'supplier')->get();

        if (isset($role[0])) {
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
    public function show($id)
    {
        $owner =  Owner::find($id);
        $val = $owner;
        $owner_bank_details = SupplierBankDetails::where('supplier_id', $owner->id)->get();
        $opening_balances = SupplierOpeningBalanceFY::where('supplier_id', $owner->id)->get();
        return view('settings.vendors.show', compact('val', 'owner_bank_details','opening_balances'));
    }

    public function fyEditOpeningBalance($id,$fy_id){
        $opening_balances = SupplierOpeningBalanceFY::where('supplier_id', $id)->where('fys_id',$fy_id)->first();
        return view('settings.vendors.fys_edit', compact('opening_balances'));
    }
    public function fyUpdateOpeningBalance(Request $request,$id){
        $this->validate($request,[
            'amount' => 'required|integer'
        ]);
        $supplier_details  = SupplierOpeningBalanceFY::find( $id);
        $supplier_details->update([
            'amount' => $request->amount
        ]);

        $request->session()->flash('success','Successfully Updated the opening balance');
        return redirect('/settings/vendors/'.$supplier_details->supplier_id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Owner  $owner
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $details = Owner::find($id);
        $users = User::role(['administrator', 'b2c', 'manager', 'marketing', 'staff', 'accounts'])->pluck('first_name', 'id')->all();
        $airports = Airport::distinct('code')->where('countryName', 'INDIA')->select('id', 'name', 'code', 'cityName')->get();
        $states = State::distinct('state')->pluck('id', 'name')->all();
        return view('settings.vendors.create_third_party', compact('details', 'states', 'airports', 'users'));
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


        $details = [
            'name' => $request->name
        ];

        $resp =  $owner->update($details);
        if ($resp) {
            $request->session()->flash('success', 'Successfully Updated');
        } else
            $request->session()->flash('error', 'Opps something went wrong');

        return redirect(route('vendors.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Owner  $owner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Owner $owner)
    {
        //
    }



    public function uploadMedia(Request $request)
    {
        $image_path = Storage::disk('s3')->put('vendors',$request->file);
        return response()->json($image_path);
    }



    // This is the method that corresponds to the api-vendors.show route
    public function apiLogsVendorShow()
    {
        $v2APILogs = V2APIBookingRequestLog::orderBy('id', 'DESC')->whereNotNull('api_request')
            ->simplePaginate(50);
        $v1APILogs = V1APIBookingRequestLog::orderBy('id', 'DESC')->whereNotNull('api_request')
            ->simplePaginate(50);


        $data_v1 = [];

        foreach ($v1APILogs as $key1 => $val1)
        {

            $api_response =json_decode($val1->api_response);

            // if ($val1->gfs_response) {
            //     $gfs_response = json_decode($val1->gfs_response);
            //     if (gettype($gfs_response) == 'string') {
            //         $gfs_response = json_decode(json_decode($val1->gfs_response));
            //     }
            // } else {
            //     $gfs_response = false;
            // }

            // if ($gfs_response) {
            //     if (property_exists($gfs_response, 'success')) {
            //         if ($gfs_response->success) {
            //             $gfs_response  = $gfs_response;
            //             $gfs_response_type = 'SUCCESS';
            //             $gfs_response = $val1->gfs_response;
            //         } else {
            //             $gfs_response_type = 'FAILED';
            //             $gfs_response = $val1->gfs_response;
            //         }
            //     } else {
            //         $gfs_response_type = 'FAILED';
            //         $gfs_response = $val1->gfs_response;
            //     }
            // } else {
            //     $gfs_response_type = 'FAILED';
            //     $gfs_response = $val1->gfs_response;
            // }

            // dd($api_response);
            if ($api_response !== null && $api_response->success) {
                $vendor_response_type = 'SUCCESS';
                $vendor_response = $val1->api_response;
            } else {
                $vendor_response_type = 'FAILED';
                $vendor_response = $val1->api_response;
            }
            $temp_val = [
                'agent_id' => Agent::find($val1->agent_id)->code,
                'departure_date' => $val1->departure_date,
                'departure_time' => $val1->departure_time,
                'arrival_date' => $val1->arrival_date,
                'arrival_time' => $val1->arrival_time,
                'origin' => $val1->origin,
                'destination' => $val1->destination,
                'childs' => $val1->childs,
                'id' => $val1->id,
                'infants' => $val1->infants,
                'adult_price' => $val1->adult_price,
                'child_price' => $val1->child_price,
                'infant_price' => $val1->infant_price,
                'total' => $val1->total,
                'paxes' => $val1->paxes,
                'airline_code' => $val1->airline_code,
                'flight_number' => $val1->flight_number,
                'api_response' =>  $api_response,
                'status' => $val1->status,
                'created_at' => $val1->created_at,
                'vendor_response_type' => $vendor_response_type,
                'vendor_response' => $vendor_response,
                'vendor_request' => $val1->api_request

            ];
            array_push($data_v1, $temp_val);
        }
        $data = [];
        foreach ($v2APILogs as $key => $val) {
            $api_response = json_decode(json_decode($val->api_response));

            if ($val->gfs_response) {
                $gfs_response = json_decode($val->gfs_response);
                if (gettype($gfs_response) == 'string') {
                    $gfs_response = json_decode(json_decode($val->gfs_response));
                }
            } else {
                $gfs_response = false;
            }

            if ($gfs_response) {
                if (property_exists($gfs_response, 'success')) {
                    if ($gfs_response->success) {
                        $gfs_response  = $gfs_response;
                        $gfs_response_type = 'SUCCESS';
                        $gfs_response = $val->gfs_response;
                    } else {
                        $gfs_response_type = 'FAILED';
                        $gfs_response = $val->gfs_response;
                    }
                } else {
                    $gfs_response_type = 'FAILED';
                    $gfs_response = $val->gfs_response;
                }
            } else {
                $gfs_response_type = 'FAILED';
                $gfs_response = $val->gfs_response;
            }


            if ($api_response->success) {
                $vendor_response_type = 'SUCCESS';
                $vendor_response = $val->api_response;
            } else {
                $vendor_response_type = 'FAILED';
                $vendor_response = $val->api_response;
            }
            $temp_val = [
                'agent_id' => Agent::find($val->agent_id)->code,
                'departure_date' => $val->departure_date,
                'departure_time' => $val->departure_time,
                'arrival_date' => $val->arrival_date,
                'arrival_time' => $val->arrival_time,
                'origin' => $val->origin,
                'destination' => $val->destination,
                'childs' => $val->childs,
                'id' => $val->id,
                'infants' => $val->infants,
                'adult_price' => $val->adult_price,
                'child_price' => $val->child_price,
                'infant_price' => $val->infant_price,
                'total' => $val->total,
                'paxes' => $val->paxes,
                'airline_code' => $val->airline_code,
                'flight_number' => $val->flight_number,
                'api_response' =>  $api_response,
                'status' => $val->status,
                'created_at' => $val->created_at,
                'vendor_response_type' => $vendor_response_type,
                'vendor_response' => $vendor_response,
                'vendor_request' => $val->api_request,
                'gfs_response_type' => $gfs_response_type,
                'gfs_response' => $val->gfs_response,
                'gfs_request' => $val->gfs_request
            ];
            array_push($data, $temp_val);
        }



        return view('settings.api-vendorscount.shown', compact('data', 'v2APILogs', 'v1APILogs','data_v1'));
    }

    public function apiLogsVendorShowResult(Request $request)
    {
        $data = json_decode($request->data);
        if (gettype($data) == 'string') {
            $data = json_decode($data);
        }

        return response()->json($data);
    }
}
