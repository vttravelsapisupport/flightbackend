<?php

namespace App\Http\Controllers\FlightTicket;

use App\Http\Controllers\Controller;
use App\Models\FlightTicket\Destination;
use App\Models\FlightTicket\LivePNRStatus;
use App\Models\FlightTicket\Owner;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PNRStatusController extends Controller
{

    public function __construct() {
        $this->middleware('permission:namelist show', ['only' => ['index']]);
    }

    public function index(Request $request){

        $destinations = Destination::where('status', 1)->get();

        $owners       = Owner::pluck('name', 'id')->all();
        $suppliers    = Owner::where('is_third_party', '=', 1)->get();


        $isSearched = $request->search;
        $details =[];
        if($isSearched)
        {
            $q  =  \App\PurchaseEntry::join('destinations as d', 'd.id', '=', 'purchase_entries.destination_id')
                ->join('airlines as a', 'a.id', '=', 'purchase_entries.airline_id')
                ->join('owners as o', 'o.id', '=', 'purchase_entries.owner_id')
                ->where('o.is_third_party', '!=', '2')
                ->whereIn('purchase_entries.owner_id', [3,84])
                ->orderBy('purchase_entries.travel_date', 'ASC');

            if ($request->has('destination_id') && $request->destination_id != '')
                $q->where('purchase_entries.destination_id', $request->destination_id);

            if ($request->has('entry_date') && $request->entry_date != '')
                $q->whereDate('purchase_entries.created_at', Carbon::parse($request->entry_date));


            if ($request->has('flight_no') && $request->flight_no != '')
                $q->where('purchase_entries.flight_no', $request->flight_no);

            if ($request->has('owner_id') && $request->owner_id != '')
                ;

            if ($request->has('supplier_id') && $request->supplier_id != '')
                $q->where('purchase_entries.owner_id', $request->supplier_id);

            if ($request->has('pnr_no') && $request->pnr_no != '')
                $q->where('purchase_entries.pnr', $request->pnr_no);

            if ($request->has('travel_date_from') && $request->travel_date_from != '' && $request->has('travel_date_to') && $request->travel_date_to != '') {
                $from = Carbon::parse($request->travel_date_from);
                $to   = Carbon::parse($request->travel_date_to);
                $q->whereBetween('purchase_entries.travel_date', [$from, $to]);

                if($from->format('Y-m-d') <= '2023-03-31' ||
                    $to->format('Y-m-d') <= '2023-03-31'
                ){
                    $data = collect([]);
                    return view('flight-tickets.pnr-status.index',compact('suppliers', 'owners', 'destinations', 'details'));
                }
            }else{
                $q->whereDate('purchase_entries.travel_date','>=','2023-04-01');
            }
            $q->where('purchase_entries.airline_id',1);
//            if ($request->has('airline') && $request->airline != '')


            if ($request->has('exclude_zero') && $request->exclude_zero != '')
                $q->where('purchase_entries.quantity', '>', 0);

            $details = $q
                ->select(
                    'purchase_entries.created_at',
                    'purchase_entries.flight_no',
                    'd.name as destination_name',
                    'a.name as airline_name',
                    'purchase_entries.pnr',
                    'purchase_entries.quantity',
                    'purchase_entries.base_price',
                    'purchase_entries.tax',
                    'purchase_entries.cost_price',
                    'purchase_entries.sell_price',
                    'purchase_entries.travel_date',
                    'purchase_entries.arrival_date',
                    'purchase_entries.departure_time',
                    'purchase_entries.arrival_time',
                    'o.name as owner_name',
                    'o.is_third_party as owner_type',
                    'purchase_entries.flight_route',
                    'purchase_entries.name_list',
                    'purchase_entries.id',
                    DB::raw('(select type from purchase_entry_statuses where purchase_entry_id  =   purchase_entries.id   order by id desc limit 1) as FlightStatus')
                )
                ->simplePaginate(50);
        }

        return view('flight-tickets.pnr-status.index',compact('suppliers', 'owners', 'destinations', 'details'));
    }

    public function show(Request $request,$id){
        $purchase_id = $id;
        $purchase_details = \App\PurchaseEntry::find($purchase_id);


        // check airline -- spicejet
        if($purchase_details->airline_id != 1){
            return  abort(403,'Please PurchaseEntry whose Airline is SPICEJET');
        }
        $previous_entry_check = LivePNRStatus::where('purchase_id',$id)->orderBy('id','DESC')->first();
        if($previous_entry_check){
                // check difference of the time
                $now = Carbon::now();
                $created_at= $previous_entry_check->created_at;
                $diff = $now->diffInMinutes($created_at);
                if($diff < 240){
                   return  abort(403,'We have retrieved the information within 4hrs for '.$purchase_details->pnr .' PNR. Please try after '.(240-$diff) .' min. ');
               }

        }
        $vendor_detail = $purchase_details->vendor;

        $isVendorThirdParty = ($purchase_details->owner_id == 3 ||  $purchase_details->owner_id == 84);
        // check stock owner
        if(!$isVendorThirdParty){
            return  abort(403,'Please select SG(IXBSF96000) or CPNIXB0036 (SG) stock');
        }
        // check PNR Length
        $pnr = $purchase_details->pnr;
        $pnr_length = strlen($purchase_details->pnr);


        if($pnr_length != 6 && $pnr_length != 5)
            return  abort(403,'PNR Number should be of length 5 or 6. Current PNR Length is '.$pnr_length);

        // call api to get result;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://gfsspicejetpnrdetailsapi.azurewebsites.net/?compact=false',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'pnr='.$pnr,
            CURLOPT_HTTPHEADER => array(
                'api-key: 8P9USD2SRAWD4DIG79Q9EDZQN2M8F5',
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $data = json_decode($response);
        if($data->success){
            $items = $data->items;
            $details = '';
            $passengers = [];
            foreach($items as $k => $i){
                $temp_data = [
                    'purchase_id' => $purchase_id ,
                    'flight_no' => $i->flight_no,
                    'fare_type' => $i->fare_type,
                    'pnr_status' => $i->pnr_status,
                    'travel_date' => $i->travel_date,
                    'from_terminal' => $i->from_terminal,
                    'to_terminal' => $i->to_terminal,
                    'departure_time' => $i->departure_time,
                    'arrival_time' => $i->arrival_time,
                    'current_flight_status' => $i->current_flight_status,
                    'departure_date_diff' => $i->departure_date_diff,
                    'pnr' => $i->pnr,
                ];
                $details  = $temp_data;
                break;
            }
            foreach($items as $k => $i){
                $temp_data = [
                    'passenger_name' => $i->passenger_name,
                    'passport_no' => $i->passport_no,
                    'passenger_type' => $i->passenger_type,
                    'passenger_gender' => $i->passenger_gender,
                    'additional_services_purchased' => $i->additional_services_purchased
                ];
                array_push($passengers,$temp_data);
            }
            return view('flight-tickets.pnr-status.show',compact('items','details','passengers'));
        }else{
            // this is important !
            return redirect()->back()->with('error', 'Could not fetch details for ' . $pnr . '.');
        }




        // display the result;
        // ask the user to save the information
    }

    public function store(Request $request){

        // delete the old data

        $passengers = [];
        foreach($request->passenger_name as $i => $p){
            $temp_data = [
                'passenger_name' => $p,
                'passenger_type' => $request->passenger_type[$i],
                'gender' => $request->passenger_gender[$i],
                'passport_no' => $request->passport_no[$i],
                'additional_services_purchased' => $request->additional_services_purchased[$i],
            ];
            array_push($passengers,$temp_data);
        }

        $isModified = 1;
        $delete_previous_data = LivePNRStatus::where('purchase_id',$request->purchase_id)->delete();


        $possibleFormats = [

            'D d M, Y',
            'l j M, Y', // Assuming "Sat" corresponds to "Saturday"
            // Add more formats as needed
        ];
        $parsedDate = null;
        foreach ($possibleFormats as $format) {
            $parsedDate = Carbon::createFromFormat($format, $request->travel_date);
            if ($parsedDate !== false) {
                break; // Exit the loop if a valid format is found
            }
        }

        if ($parsedDate) {
            $formattedDate = $parsedDate->format('Y-m-d');
        } else {
            dd( "Unable to parse the travel_date string.");
        }

        $data = [
            'purchase_id'=> $request->purchase_id,
            'total_pax_count' => count($passengers),
            'flight_no'   => $request->flight_no,
            'travel_date' => $formattedDate,
            'source' =>  $request->from_terminal,
            'destination' =>  $request->to_terminal,
            'dep_time' =>  $request->departure_time,
            'arrival_time' =>  $request->arrival_time,
            'current_flight_status' =>  $request->current_flight_status,
            'pnr_status' =>  $request->pnr_status,
            'passengers' =>  $passengers,
            'status'=> $isModified
        ];

         $data1 =  LivePNRStatus::create($data);
         $request->session()->flash('success','Successfully Saved');
         return  redirect(route('pnr-status.index'));




    }
}
