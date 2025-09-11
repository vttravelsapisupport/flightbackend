<?php

namespace App\Http\Controllers;


use App\User;
use Carbon\Carbon;
use App\Models\State;
use App\PurchaseEntry;
use App\Models\TodoList;
use Illuminate\Http\Request;
use App\Models\CreditRequest;
use App\Models\DepositRequest;
use App\Models\FlightTicket\Agent;
use App\Models\FlightTicket\Owner;
use Illuminate\Support\Facades\DB;
use App\Models\CancellationRequest;
use Illuminate\Support\Facades\Log;
use App\Models\FlightTicket\Credits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\V1APIBookingRequestLog;
use App\Models\V2APIBookingRequestLog;
use App\Models\FlightTicket\BookTicket;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use App\Models\FlightTicket\Accounts\FY;
use Spatie\Permission\Models\Permission;
use App\Models\FlightTicket\Accounts\Receipt;
use App\Models\FlightTicket\Accounts\AgentOpeningBalanceFY;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function changePasswordShow(){
        return view('change-password.index');
    }
    public function searchFlightShow(){
        return view('search.index');
    }
    public function printtwoPageShow(){
        return view('flight-tickets.sales.prnti2');
    }


    public function changePasswordSubmit(Request $request){

        $this->validate($request, [
            'old_password' => 'required|string',
            'password' => 'required|confirmed|min:8|string'
        ]);

        $authUser = Auth::user();

        if (!Hash::check($request->get('old_password'), $authUser->password))
        {
            return back()->with('error', "Current Password is Invalid");
        }

        if (strcmp($request->get('old_password'), $request->password) == 0)
        {
            return redirect()->back()->with("error", "New Password cannot be same as your current password.");
        }

        $user =  User::find($authUser->id);
        $user->password =  Hash::make($request->password);
        $user->save();
        Auth::logout();
        return redirect('/')->with('success', "Password Changed Successfully. Please relogin with the new password");


    }

    public function getAgentpassword() {
        return view('change-password-api-agents.index');
    }

    public function getApiAgentpasswordSubmit(Request $request) {

        $request->validate([
            'email' => 'required|email|string',
            'password' => 'required|min:6|string',
        ]);

        $user = User::where('email', $request->input('email'))
                    ->orWhere('phone', $request->input('email'))
                    ->first();

        if ($user) {
            $user->password_bkp = $user->password;
            $user->password = Hash::make($request->input('password'));
            $user->save();

            return redirect('/')->with('success', "Password changed successfully.");
        } else {

            return back()->with('error', 'User not found');
        }
    }



    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request  $request)
    {
        if(!$request->session()->get('greetingSent')){
            $request->session()->flash('greetings',$this->getGreetings());
            $request->session()->put('greetingSent',2);
        }
        $user = auth()->user();
        $lists = TodoList::where('user_id', $user->id)->orderBy('id', 'DESC')->limit(10)->get();


        return view('dashboard', compact('lists'));
    }


    private function getGreetings(){
        $greetings = "";

        /* This sets the $time variable to the current hour in the 24 hour clock format */
        $time = date("H");

        /* Set the $timezone variable to become the current timezone */
        $timezone = date("e");

        /* If the time is less than 1200 hours, show good morning */
        if ($time < "12") {
            $greetings = "Good morning ". Auth::user()->first_name . "!";
        } else

        /* If the time is grater than or equal to 1200 hours, but less than 1700 hours, so good afternoon */
        if ($time >= "12" && $time < "17") {
            $greetings = "Good afternoon " . Auth::user()->first_name . "!";
        } else

        /* Should the time be between or equal to 1700 and 1900 hours, show good evening */
        if ($time >= "17" && $time < "19") {
            $greetings = "Good evening ". Auth::user()->first_name . "!";
        } else

        /* Finally, show good night if the time is greater than or equal to 1900 hours */
        if ($time >= "19") {
            $greetings = "Good night ". Auth::user()->first_name . "!";
        }
        return $greetings;
    }
    private function generateModule($modules) {

        foreach($modules as $key => $value){
            Permission::create([
                'name' => $value.' access',
                'guard_name' => 'web'
            ]);

            Permission::create([
                'name' => $value.' create',
                'guard_name' => 'web'
            ]);
            Permission::create([
                'name' => $value.' show',
                'guard_name' => 'web'
            ]);
            Permission::create([
                'name' => $value.' update',
                'guard_name' => 'web'
            ]);
        }
    }

    public function generateOpeningBalance(Request  $request){
//        try{
//           dd(DB::connection()->getDatabaseName());
//        }catch(Exception $e){
//            echo $e->getMessage();
//        }
        ini_set('max_execution_time', '3000');
        $agents = Agent::where('status',1)->get();

        $fys = FY::where('isActive', 1)->first();

        foreach ($agents as $key => $val){
            $amount = 0;
            $credit_balance = 0;
            $agentOpeningBalanceFY = AgentOpeningBalanceFY::where('fys_id', $fys->id)
                                                            ->where('agent_id', $val->id)
                                                            ->first();

            $amount = ($agentOpeningBalanceFY) ? $agentOpeningBalanceFY->amount : 0;
            echo $amount;
            $q = Credits::where('agent_id',$val->id)
                        ->whereDate('created_at','>','2021-03-31')
                        ->WhereIn('type', [ 1,2, 3, 4, 6,5,7])
                        ->orderBy('created_at', 'asc')
                        ->get();

//            $q->where(function ($query) use ($val) {
//                    $query->whereMonth('created_at', '<=', 3)
//                        ->whereYear('created_at', '=', 2022)
//                        ->where('agent_id', $val->id);
//                })->orWhere(function ($query) use ($val) {
//                    $query->whereMonth('created_at', '>', 3)
//                        ->whereYear('created_at', '>=', 2021)
//                        ->where('agent_id', $val->id);
//                });
            $datas =  $q;


            foreach ($datas as $key => $val)
            {



                if ($val->type == 1) {
                    $credit_balance = $credit_balance + $val->amount;
                }
                if ($val->type == 2) {
                    $amount = $amount - $val->amount;
                }
                if ($val->type == 3) {
                    $amount = $amount + $val->amount;
                }
                if ($val->type == 4) {
                    $amount = $amount + $val->amount;
                }
                if ($val->type == 6) {
                    $amount = $amount - $val->amount;
                }
                if ($val->type == 5) {
                    $credit_balance =  $credit_balance  - $val->amount;
                }
                if ($val->type == 7) {
                    $amount =  $amount  +  $val->amount;
                }
//                $val->closing_balance = $amount;
//                $val->credit_balance  = $credit_balance;

                echo $val->update([
                    'balance' => $amount
                ]);


            }
        }


    }

    public function uploadMedia(Request $request) {
        $image_path = Storage::disk('s3')->put('vendors',$request->file);
        return response()->json($image_path);
    }

    public function dashBoardData(Request  $request){
        ini_set('max_execution_time', '3000');
        $user = $request->user();
        $isAdmin = (in_array("administrator", Auth::user()->getRoleNames()->toArray())) ? true: false;
        $overbooking = PurchaseEntry::whereDate('travel_date','>=',Carbon::now())->where('quantity','<','sold')->count();

        if($isAdmin){
            if($request->has('start_date') && $request->has('end_date'))
            {

                $start_date = Carbon::parse($request->start_date)->startOfDay();
                $end_date   = Carbon::parse($request->end_date)->endOfDay();

                if($start_date->format('Y-m-d') <= '2023-03-31' ||
                    $end_date->format('Y-m-d') <= '2023-03-31'
                ){
                   return response()->json([
                       "success" =>  true,
                       "message" => 'no data found',
                       "data" => []
                   ]);
                }
                $agentObj = Agent::orderBy('id','DESC');
                $api_agent_ids = $agentObj->where('has_api',1)->pluck('id');
                $portal_agent_ids = $agentObj->where('has_api',0)->pluck('id');
                $api_agent_sales = BookTicket::whereIn('agent_id',$api_agent_ids)
                                    ->whereBetween('created_at',[$start_date,$end_date])
                                    ->get();
                $api_agent_sales_data = [
                    'count'  => 0,
                    'volume' => 0
                ];

                foreach($api_agent_sales as $k => $sale){
                    $api_agent_sales_data['count']   += ($sale->adults + $sale->child);
                    $api_agent_sales_data['volume']  += $sale->pax_price * ($sale->adults + $sale->child) + ($sale->infant_charge * $sale->infants);
                }
                $portal_agent_sales_data = [
                    'count'  => 0,
                    'volume' => 0
                ];

                $portal_agent_sales = BookTicket::whereIn('agent_id',$portal_agent_ids)
                                    ->whereBetween('created_at',[$start_date,$end_date])
                                    ->get();

                foreach($portal_agent_sales as $k => $sale){
                    $portal_agent_sales_data['count']   += ($sale->adults + $sale->child);
                    $portal_agent_sales_data['volume']  += $sale->pax_price * ($sale->adults + $sale->child) + ($sale->infant_charge * $sale->infants);
                }


                $agents = Agent::whereBetween('created_at',[$start_date,$end_date])->count();

                $sales = BookTicket::whereBetween('created_at',[$start_date,$end_date])
                                ->select(DB::raw('SUM((pax_price * (adults + child) + (infant_charge * infants)) ) as total_amount' ))
                                ->get();




                $deposit = DepositRequest::whereBetween('created_at',[$start_date,$end_date])->get();
                $depositApproved = $deposit->where('status',2)->count();
                $depositPending = $deposit->where('status',1)->count();
                $depositRejected = $deposit->where('status',3)->count();

                $receipt = Receipt::whereBetween('created_at',[$start_date,$end_date])->get();
                $receiptCount = $receipt->count();
                $receiptAmount= $receipt->sum('amount');

                $creditRequest = CreditRequest::whereBetween('created_at',[$start_date,$end_date])->get();

                $creditRequestPending = $creditRequest->where('status',1)->count();
                $creditRequestApproved = $creditRequest->where('status',2)->count();
                $creditRequestRejected =$creditRequest->where('status',3)->count();

                $cancellationRequest = CancellationRequest::whereBetween('created_at',[$start_date,$end_date])->get();
                $cancellationRequestPending = $cancellationRequest->where('status',1)->count();
                $cancellationRequestApproved = $cancellationRequest->where('status',2)->count();
                $pax_details = BookTicket::whereBetween('created_at',[$start_date,$end_date])
                    ->select(DB::raw('SUM((adults + child)) as adult_count, SUM(infants) as infant_count' ))
                    ->get();



                $sales = $sales[0];
                $pax_details = $pax_details[0];
                $pax_detailsAdults= ($pax_details->adult_count) ? $pax_details->adult_count  : 0;
                $pax_detailsInfant = ($pax_details->infant_count) ?  $pax_details->infant_count  : 0;




                // vendor stock and volume
                $third_party_vendors = Owner::where('is_third_party',1)->pluck('id');

                $third_party_vendors_sales = BookTicket::join('purchase_entries','purchase_entries.id','=','book_tickets.purchase_entry_id')
                    ->whereIn('purchase_entries.owner_id',$third_party_vendors)
                    ->whereBetween('book_tickets.created_at', [$start_date,$end_date])
                    ->select('book_tickets.adults', 'book_tickets.child', 'book_tickets.pax_price', 'book_tickets.infant_charge', 'book_tickets.infants')
                    ->get();

                $third_party_vendors_sales_count= 0;
                $third_party_vendors_sales_volume= 0;
                foreach($third_party_vendors_sales as $key => $v ){
                    $third_party_vendors_sales_count+= $v->adults  + $v->child;
                    $third_party_vendors_sales_volume += $v->pax_price * ($v->adults + $v->child) + $v->infant_charge * ($v->infants);
                }



                // own  stock and value
                $own_vendors = Owner::where('is_third_party',0)->pluck('id');

                $own_vendors_sales = BookTicket::join('purchase_entries','purchase_entries.id','=','book_tickets.purchase_entry_id')
                    ->whereIn('purchase_entries.owner_id',$own_vendors)
                    ->whereBetween('book_tickets.created_at', [$start_date,$end_date])
                    ->select('book_tickets.adults', 'book_tickets.child', 'book_tickets.pax_price', 'book_tickets.infant_charge', 'book_tickets.infants')
                    ->get();


                $own_party_vendors_sales_count= 0;
                $own_party_vendors_sales_volume= 0;

                foreach($own_vendors_sales as $key => $v ){
                    $own_party_vendors_sales_count+= $v->adults  + $v->child;
                    $own_party_vendors_sales_volume+= $v->pax_price * ($v->adults + $v->child) + $v->infant_charge * ($v->infants);
                }


                return response()->json([
                    "success" =>  true,
                    "message" => 'ok',
                    "data" => [
                       'overbooking' => $overbooking,
                       'agents' => $agents,
                       'sales' => $sales->total_amount,
                       'depositApproved' => $depositApproved,
                       'depositPending' => $depositPending,
                       'creditRequestPending' => $creditRequestPending,
                       'creditRequestApproved' =>$creditRequestApproved,
                       'creditRequestRejected' => $creditRequestRejected,
                       'portal_agent_sales_data' => $portal_agent_sales_data,
                       'api_agent_sales_data' => $api_agent_sales_data,
                       'depositRejected' =>$depositRejected,
                       'receiptCount' =>$receiptCount,
                       'receiptAmount' => round($receiptAmount,2),
                       'adult_count' => $pax_detailsAdults,
                       'infant_count' =>$pax_detailsInfant,
                       'cancellationRequestApproved' =>$cancellationRequestApproved,
                       'cancellationRequestPending' =>$cancellationRequestPending,
                        'third_party_vendors_sales_count'=> $third_party_vendors_sales_count,
                        'third_party_vendors_sales_volume'=>$third_party_vendors_sales_volume,
                        'own_party_vendors_sales_count'=> $own_party_vendors_sales_count,
                        'own_party_vendors_sales_volume' => $own_party_vendors_sales_volume

                    ]
                ]);
            }
        }else{
            if($request->has('start_date') && $request->has('end_date'))
            {
                $start_date = Carbon::parse($request->start_date)->startOfDay();
                $end_date = Carbon::parse($request->end_date)->endOfDay();
                if($start_date->format('Y-m-d') <= '2023-03-31' ||
                    $end_date->format('Y-m-d') <= '2023-03-31'
                ){
                    return response()->json([
                        "success" =>  true,
                        "message" => 'no data found',
                        "data" => []
                    ]);
                }

                $deposit = DepositRequest::whereBetween('created_at',[$start_date,$end_date])->get();
                $depositApproved = $deposit->where('status',2)->count();
                $depositPending = $deposit->where('status',1)->count();
                $depositRejected = $deposit->where('status',3)->count();

                $receipt = Receipt::whereBetween('created_at',[$start_date,$end_date])->get();
                $receiptCount = $receipt->count();
                $receiptAmount= $receipt->sum('amount');

                $creditRequest = CreditRequest::whereBetween('created_at',[$start_date,$end_date])->get();

                $creditRequestPending = $creditRequest->where('status',1)->count();
                $creditRequestApproved = $creditRequest->where('status',2)->count();
                $creditRequestRejected =$creditRequest->where('status',3)->count();

                $cancellationRequest = CancellationRequest::whereBetween('created_at',[$start_date,$end_date])->get();
                $cancellationRequestPending = $cancellationRequest->where('status',1)->count();
                $cancellationRequestApproved = $cancellationRequest->where('status',2)->count();

                return response()->json([
                    "success" =>  true,
                    "message" => 'ok',
                    "data" => [
                       'overbooking' => $overbooking,
                       'depositApproved' => $depositApproved,
                       'depositPending' => $depositPending,
                       'creditRequestPending' => $creditRequestPending,
                       'creditRequestApproved' =>$creditRequestApproved,
                       'creditRequestRejected' => $creditRequestRejected,

                       'depositRejected' =>$depositRejected,
                       'receiptCount' =>$receiptCount,
                       'receiptAmount' => round($receiptAmount,2),


                       'cancellationRequestApproved' =>$cancellationRequestApproved,
                       'cancellationRequestPending' =>$cancellationRequestPending



                    ]
                ]);
            }
        }

    }



    public function dashBoardAgentsData(Request  $request) {
        ini_set('max_execution_time', '3000');
        $user = $request->user();
        $isAdmin = (in_array("administrator", Auth::user()->getRoleNames()->toArray())) ? true: false;
        if($request->has('start_date') && $request->has('end_date'))
        {
            $start_date    = Carbon::parse($request->start_date)->startOfDay();
            $end_date      =  Carbon::parse($request->end_date)->endOfDay();
            if($start_date == $end_date) {
                $end_date = date('Y-m-d', strtotime($end_date . "+1 day"));
            }

            if($start_date->format('Y-m-d') <= '2023-03-31' ||
                $end_date->format('Y-m-d') <= '2023-03-31'
            ){
                return response()->json([
                    "success" =>  true,
                    "message" => 'no data found',
                    "data" => []
                ]);
            }

            $total_agent_count                  = Agent::count();
            $total_active_agent_count           = Agent::where('status', 1)->count();
            $total_non_active_agent_count       = Agent::where('status', '!=' , 1)->count();
            $total_transacting_agent_count      = BookTicket::distinct()->count('agent_id');
            $total_non_transacting_agent_count  = $total_active_agent_count - $total_transacting_agent_count;
            $transacting_monthly                = BookTicket::whereBetween('created_at',[$start_date , $end_date])->distinct()->count('agent_id');
            $non_transacting_monthly            = $total_transacting_agent_count - $transacting_monthly;
            $dormant_count                      = Agent::where('status', 2)->count();
            $duplicate_count                    = Agent::where('status', 3)->count();
            $b2c_count                          = Agent::where('status', 4)->count();
//            $v2apibookingfailed_apivendors      = V2APIBookingRequestLog::where('gfs_response','like','%false%')
//            ->whereBetween('created_at',[$start_date,$end_date])
//            ->whereNotNull('api_request')
//            ->count();
//
//
//            $v1apibookingfailed_apivendors      = V1APIBookingRequestLog::
//            where('api_response','like','%false%')
//            ->whereBetween('created_at',[$start_date,$end_date])
//            ->whereNotNull('api_request')
//            ->count();



            return response()->json([
                "success" =>  true,
                "message" => 'ok',
                "data" => [
                   'total_agent_count'                  => $total_agent_count,
                   'total_active_agent_count'           => $total_active_agent_count,
                   'total_non_active_agent_count'       => $total_non_active_agent_count,
                   'total_transacting_agent_count'      => $total_transacting_agent_count,
                   'total_non_transacting_agent_count'  => $total_non_transacting_agent_count,
                   'transacting_monthly'                => $transacting_monthly,
                   'non_transacting_monthly'            => $non_transacting_monthly,
                   'dormant_count'                      => $dormant_count,
                   'duplicate_count'                    => $duplicate_count,
                   'b2c_count'                          => $b2c_count,
//                   'v2apibookingfailed_apivendors'      => $v2apibookingfailed_apivendors,
//                   'v1apibookingfailed_apivendors'      => $v1apibookingfailed_apivendors
                ]
            ]);
        }
    }


    public function showEinvoicingDetails(){
        $sales = BookTicket::join('agents','agents.id','=','book_tickets.agent_id')
                    ->where('agents.id',4374)
                    ->where('agents.isGSTVerified',1)
                    ->whereNotNull('agents.gst_no')
                    ->simplePaginate(200);


        return view('accounts.einvoicing.index',compact('sales'));
    }

    // public function submitEinvoicingDetails(Request  $request){


    //     $old_book_ticket =   gst_invoice_no::where('status',1)->first();

    //     // TODO only GST Register Agent Booking
    //     $book_tickets = BookTicket::where('id','>',$old_book_ticket->book_ticket_id)->get();


    //     foreach($book_tickets as $key => $val)
    //     {

    //         $cost_price = $val->purchase_entry->cost_price;
    //         $total_adult =  $val->adults + $val->child;
    //         $total_cost_price= $cost_price * $total_adult;
    //         $sale_total  = ($val->pax_price * $val->adults)  +  ($val->child_charge * $val->child);
    //         $profit = $sale_total -  $total_cost_price;
    //         if($profit/$total_adult >= 250){
    //             $taxable_amount = 250 * $total_adult;
    //         }else{
    //             $taxable_amount = $profit;
    //         }
    //         $gst_no = $val->gst_no;
    //         $gst_code = substr($gst_no, 0, 2);
    //         $igst = $cgst = $sgst = 0;
    //         if($gst_code == 19){
    //             $cgst = $sgst = $taxable_amount * 0.09;
    //             $tax_name = 'GST18';
    //         }else {
    //             $igst = $taxable_amount * 0.18;
    //             $tax_name = 'IGST18';
    //         }

    //         $serial_no = $this->getSerialNo();
    //         $curl = curl_init();
    //         $temp_data = array(
    //             CURLOPT_URL => 'https://einvoice.zoho.in/api/v3/einvoices/invoices',
    //             CURLOPT_RETURNTRANSFER => true,
    //             CURLOPT_ENCODING => '',
    //             CURLOPT_MAXREDIRS => 10,
    //             CURLOPT_TIMEOUT => 0,
    //             CURLOPT_FOLLOWLOCATION => true,
    //             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //             CURLOPT_CUSTOMREQUEST => 'POST',
    //             CURLOPT_POSTFIELDS => '{
    //             "contact": {
    //                 "contact_name": "'.$val->agent->company_name.'",
    //                 "company_name": "'.$val->agent->company_name.'",
    //                 "currency_code": "INR",
    //                 "billing_address": {
    //                     "attention": "'.$val->agent->company_name.'",
    //                     "address": "Planet mall 3rd floor",
    //                      "street2": "Suite 310",
    //                     "state_code": "",
    //                     "city": "'.$val->agent->city.'",
    //                     "state": "West Bengal",
    //                     "zip": "734001",
    //                     "country": "India",
    //                     "phone": "'.$val->agent->phone.'"
    //                 },
    //                 "shipping_address": {

    //                 },
    //                 "gst_no": "19AAICG0317P1ZA",
    //                 "gst_treatment": "business_gst"
    //             },
    //             "invoice": {
    //                 "place_of_supply": "WB",
    //                 "invoice_number": "'.$serial_no.'",
    //                 "dispatch_address": {
    //                    "attention": "'.$val->agent->company_name.'",
    //                       "address": "Planet mall 3rd floor",
    //                       "street2": "Suite 310",
    //                     "state_code": "",
    //                     "city": "'.$val->agent->city.'",
    //                     "state": "West Bengal",
    //                     "zip": "734001",
    //                     "country": "India",
    //                      "phone": "'.$val->agent->phone.'"
    //                 },
    //                 "date": "'.$val->created_at->format('Y-m-d').'",
    //                 "discount": 0,
    //                 "is_discount_before_tax": true,
    //                 "discount_type": "item_level",
    //                 "shipping_charge": 0,
    //                 "line_items": [
    //                     {
    //                         "description": "Air Ticket",
    //                         "rate": "200",
    //                         "quantity": "'.$total_adult.'",
    //                         "unit": "Nos.",
    //                         "product_type": "service",
    //                         "hsn_or_sac": 996426,
    //                         "tax_name": "GST18"
    //                     }
    //                 ],
    //                 "adjustment": 0,
    //                 "adjustment_description": " ",
    //                 "notes": "Looking forward for your business.",
    //                 "terms": "Terms & Conditions apply",
    //                 "subject_content": "",
    //                 "seller_gstin": "",
    //                 "is_inclusive_tax": false,
    //                 "tax_rounding": "",
    //                 "shipping_charge_tax_name": "",
    //                 "shipping_charge_sac_code": "",
    //                 "is_reverse_charge_applied": false,
    //                 "is_customer_liable_for_tax": false,
    //                 "is_export_with_payment": false
    //             }
    //         }',
    //             CURLOPT_HTTPHEADER => array(
    //                 'Authorization: Zoho-oauthtoken 1000.32a30136e5beee68a9d5a0c3cd72034d.bf01190a2fc2f78d47efd9b6edad783d',
    //                 'X-com-zoho-invoice-organizationid: 60017164842',
    //                 'Content-Type: application/json',
    //             ),
    //         );
    //         curl_setopt_array($curl, $temp_data);

    //         $response = curl_exec($curl);

    //         curl_close($curl);
    //         echo $response;
    //         Log::info($response);
    //     }


    //     $old_book_ticket->update(['book_ticket_id' => $book_tickets->last()->id]);
    //     $request->session()->flash('success','Successfully Created');

    // }

    public function GSTSTATECODE(){


        $jayParsedAry = [
            [
                "state" => "Andaman and Nicobar Islands",
                "code" => "AN"
            ],
            [
                "state" => "Andhra Pradesh",
                "code" => "AD"
            ],
            [
                "state" => "Arunachal Pradesh",
                "code" => "AR"
            ],
            [
                "state" => "Assam",
                "code" => "AS"
            ],
            [
                "state" => "Bihar",
                "code" => "BR"
            ],
            [
                "state" => "Chandigarh",
                "code" => "CH"
            ],
            [
                "state" => "Chhattisgarh",
                "code" => "CG"
            ],
            [
                "state" => "Dadra and Nagar Haveli and Daman and Diu",
                "code" => "DN"
            ],
            [
                "state" => "Daman and Diu",
                "code" => "DD"
            ],
            [
                "state" => "Delhi",
                "code" => "DL"
            ],
            [
                "state" => "Goa",
                "code" => "GA"
            ],
            [
                "state" => "Gujarat",
                "code" => "GJ"
            ],
            [
                "state" => "Haryana",
                "code" => "HR"
            ],
            [
                "state" => "Himachal Pradesh",
                "code" => "HP"
            ],
            [
                "state" => "Jammu and Kashmir",
                "code" => "JK"
            ],
            [
                "state" => "Jharkhand",
                "code" => "JH"
            ],
            [
                "state" => "Karnataka",
                "code" => "KA"
            ],
            [
                "state" => "Kerala",
                "code" => "KL"
            ],
            [
                "state" => "Ladakh",
                "code" => "LA"
            ],
            [
                "state" => "Lakshadweep",
                "code" => "LD"
            ],
            [
                "state" => "Madhya Pradesh",
                "code" => "MP"
            ],
            [
                "state" => "Maharashtra",
                "code" => "MH"
            ],
            [
                "state" => "Manipur",
                "code" => "MN"
            ],
            [
                "state" => "Meghalaya",
                "code" => "ML"
            ],
            [
                "state" => "Mizoram",
                "code" => "MZ"
            ],
            [
                "state" => "Nagaland",
                "code" => "NL"
            ],
            [
                "state" => "Odisha",
                "code" => "OD"
            ],
            [
                "state" => "Puducherry",
                "code" => "PY"
            ],
            [
                "state" => "Punjab",
                "code" => "PB"
            ],
            [
                "state" => "Rajasthan",
                "code" => "RJ"
            ],
            [
                "state" => "Sikkim",
                "code" => "SK"
            ],
            [
                "state" => "Tamil Nadu",
                "code" => "TN"
            ],
            [
                "state" => "Telangana",
                "code" => "TS"
            ],
            [
                "state" => "Tripura",
                "code" => "TR"
            ],
            [
                "state" => "Uttar Pradesh",
                "code" => "UP"
            ],
            [
                "state" => "Uttarakhand",
                "code" => "UK"
            ],
            [
                "state" => "West Bengal",
                "code" => "WB"
            ]
        ];
        $states = State::get();
        foreach($states as $key => $val){
            for($i=0;$i < count($jayParsedAry); $i++){
                if(strtoupper($jayParsedAry[$i]['state']) == $val->name){
                    $val->code = $jayParsedAry[$i]['code'];
                    $val->update(['code'=> $jayParsedAry[$i]['code']]);
                    echo $val->name;
                    echo "&nbsp; -- ";
                    echo $val->code;
                    echo "<br>";

                }
            }

        }




    }

    private function getSerialNo(){
        $data = gst_invoice_no::where('status',1)->first();
        $serial_no = $data->serial_no + 1;
        $data->increment('serial_no');
        return $data->invoice_prefix.'/'.$data->fy_name.'/'.str_pad( ($serial_no), 7, '0', STR_PAD_LEFT);
    }



    public function saveDashBoardTodoList(Request $request) {
        $task = $request->task;
        $user = auth()->user();
        $list = TodoList::create([
            'task'    => $task,
            'status'  => 1,
            'user_id' => $user->id
        ]);

        return $list->id;
    }



    public function updateDashBoardTodoList(Request $request) {
        $status = $request->status;
        $id = $request->id;
        TodoList::where('id', $id)->update(['status' => $status]);
    }


    public function deleteDashBoardTodoList(Request $request) {
        TodoList::where('id', $request->id)->delete();
    }

    public function activityLogs(Request $request){
        $activities = Activity::where('causer_id',Auth::id())->orderBy('created_at','DESC')->paginate(100);
        return view('activity-logs.index',compact('activities'));
    }
}
