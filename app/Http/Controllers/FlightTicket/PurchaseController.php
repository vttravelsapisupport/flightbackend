<?php

namespace App\Http\Controllers\FlightTicket;

use Carbon\Carbon;
use App\PurchaseEntry;
use Illuminate\Http\Request;
use App\Exports\TicketExport;
use App\Imports\TicketImport;
use App\Services\FlightService;
use App\Services\RefundService;
use App\Models\FlightTicket\Owner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\FlightTicket\Airline;
use App\Models\FlightTicket\Airport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PurchaseEntryPNRExport;
use App\Models\FlightTicket\BookTicket;
use Illuminate\Database\QueryException;
use App\Models\FlightTicket\Destination;
use App\Models\FlightTicket\PurchaseEntryStatus;
use App\Models\FlightTicket\SpiceJetPNRReconciliationDetail;
use App\Models\FlightTicket\SpiceJetPNRReconciliationSummary;
use App\Models\FlightTicket\SpiceJetPNRReconciliationDetailLog;
use App\Models\FlightTicket\SpiceJetPNRReconciliationSummaryLog;


class PurchaseController extends Controller
{

    public function purchaseExcelPreview(){
        return view('flight-tickets.purchase.excelPreview');
    }
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct() {
        $this->middleware('permission:purchase_entry show', ['only' => ['index']]);
    }

    public function index(Request $request)
    {

        //$destinations = Destination::where('status', 1)->pluck('id', 'name')->all();
        $destinations = Destination::where('status', 1)->get();
        $airlines     = Airline::where('status', 1)->pluck('id', 'name')->all();
        $owners       = Owner::pluck('name', 'id')->all();
        $suppliers    = Owner::where('is_third_party', '=', 1)->get();
        $flight_no    = PurchaseEntry::select('flight_no')->distinct()->get();
        $limit = 100;

        $q  =  \App\PurchaseEntry::join('destinations as d', 'd.id', '=', 'purchase_entries.destination_id')
            ->join('airlines as a', 'a.id', '=', 'purchase_entries.airline_id')
            ->join('owners as o', 'o.id', '=', 'purchase_entries.owner_id')
            ->orderBy('purchase_entries.created_at', 'DESC');


        if ($request->has('destination_id') && $request->destination_id != '')
            $q->where('purchase_entries.destination_id', $request->destination_id);

        if ($request->has('entry_date') && $request->entry_date != '')
            $q->whereDate('purchase_entries.created_at', Carbon::parse($request->entry_date));


        if ($request->has('flight_no') && $request->flight_no != '')
            $q->where('purchase_entries.flight_no', $request->flight_no);

        if ($request->has('owner_id') && $request->owner_id != '')
            $q->where('purchase_entries.owner_id', $request->owner_id);

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
                 return view('flight-tickets.purchase.index', compact('suppliers', 'owners', 'data', 'destinations', 'airlines', 'flight_no'));
             }
        }else {
            $q->whereDate('purchase_entries.travel_date','>=','2023-04-01');
        }

        if ($request->has('airline') && $request->airline != '')
            $q->where('purchase_entries.airline_id', $request->airline);

        if ($request->has('exclude_zero') && $request->exclude_zero != '')
            $q->where('purchase_entries.quantity', '>', 0);

        if ($request->has('result') && $request->result != ''){
            $limit = $request->result;
        }

        $q->whereNotIn('purchase_entries.id', function ($query) {
            $query->select('id')
                ->from('purchase_entries')
                ->where('owner_id', 270)
                ->whereDate('created_at', '>', '2023-10-13');
        });

        $data = $q
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
                'purchase_entries.infant',
                'purchase_entries.travel_date',
                'purchase_entries.arrival_date',
                'purchase_entries.departure_time',
                'purchase_entries.arrival_time',
                'o.name as owner_name',
                'o.is_third_party as owner_type',
                'purchase_entries.flight_route',
                'purchase_entries.namelist_status',
                'purchase_entries.name_list',
                'purchase_entries.id',
                'purchase_entries.trip_type',
                DB::raw('(select type from purchase_entry_statuses where purchase_entry_id  =   purchase_entries.id   order by id desc limit 1) as FlightStatus')
            )
            ->simplePaginate($limit);

        $query = $request->all();

        $request->session()->put('searchQuery', json_encode($query));

        return view('flight-tickets.purchase.index', compact('suppliers', 'owners', 'data', 'destinations', 'airlines', 'flight_no'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $destinations = Destination::where('status', 1)->pluck('id', 'name')->all();
        $destinations = Destination::where('status', 1)->get();
        $airlines     = Airline::where('status', 1)->pluck('id', 'name')->all();
        $owners       = Owner::where('status', 1)->select('id', 'name','is_third_party')->get();
        return view('flight-tickets.purchase.create', compact('destinations', 'airlines', 'owners'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $this->validate($request, [
            'airline_id'     => 'required|integer',
            'destination_id' => 'required|integer',
            'pnr'            => 'required|unique:purchase_entries',
            'flight_no'      => 'required',
            'travel_date'    => 'required|date|after:now',
            'name_list'      => 'required|date',
            'departure_time' => 'required',
            'arrival_time'   => 'required',
            'quantity'       => 'required|integer',
            'cost_price'     => 'required',
            'markup_price'   => 'required',
            'owner_id'       => 'required|integer',
            'flight_route'   => 'required',
            'infant_charge'  => 'required'
            //'arrival_date' => 'required',
            // 'base_price' => 'required',
            // 'tax' => 'required'
        ]);

        $sell_price = $request->cost_price + $request->markup_price;

        if(strtotime($request->departure_time) < strtotime($request->arrival_time)) {
            $arrival_date = date('Y-m-d', strtotime($request->travel_date));
        }else{
            $arrival_date = date('Y-m-d', strtotime($request->travel_date. ' + 1 days'));
        }
        $pnr_filtered = preg_replace("/[^a-zA-Z0-9]+/", "", trim($request->pnr));
        $airline = Airline::find($request->airline_id);

        $baggage_info = [
            'cabin_baggage'   => $request->cabin_baggage ? $request->cabin_baggage . 'KG' : null,
            'checkin_baggage' => $request->checkin_baggage ? $request->checkin_baggage . 'KG' : null,
            'cabin_baggage_count' => $request->cabin_baggage_count,
            'checkin_baggage_count' => $request->checkin_baggage_count,
        ];


        $flight_no =  trim($request->flight_no);
        $airline_code = trim($request->airline_code);

        $data = [
            'airline_id'        => $request->airline_id,
            'destination_id'    => $request->destination_id,
            'pnr'               => $pnr_filtered,
            'flight_no'         => $airline_code.' '.$flight_no,
            'travel_date'       => Carbon::parse($request->travel_date),
            'name_list'         => Carbon::parse($request->name_list),
            'name_list_day'     => $request->name_list_day,
            'departure_time'    => $request->departure_time,
            'arrival_time'      => $request->arrival_time,
            'quantity'         => $request->quantity,
            'available'         => $request->quantity,
            'child'             => $sell_price,
            'infant'            => $request->infant_charge,
            'cost_price'        => $request->cost_price,
            'markup_price'      => $request->markup_price,
            'sell_price'        => $sell_price,
            'owner_id'          => $request->owner_id,
            'flight_route'      => $request->flight_route,
            'purchase_entry_id' => Auth::id(),
            'arrival_date' =>  $arrival_date,
            'base_price' => $request->base_price,
            'tax' => $request->tax,
            'isOnline' => 1, // offliine
            'baggage_info' => json_encode($baggage_info),

        ];



        $resp = \App\PurchaseEntry::create($data);

        if ($resp) {
            $request->session()->flash('success', 'Successfully Saved');
        } else
            $request->session()->flash('error', 'Opps something went wrong');

        return redirect(route('purchase.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PurchaseEntry $purchaseEntry
     * @return \Illuminate\Http\Response
     */
    public function show(\App\PurchaseEntry $purchase)
    {
        return view('flight-tickets.purchase.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PurchaseEntry $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(\App\PurchaseEntry $purchase)
    {
        if( $purchase->trip_type == 2) {
            abort(403, 'Ticket Cannot be modified for Round Trip');
        }

        if($purchase->owner->is_third_party == 2) {
            abort(403, 'Ticket Cannot be modified for Third Party Supplier');
        }
        // $destinations = Destination::pluck('id', 'name')->all();
        $destinations = Destination::where('status', 1)->get();
        $airlines     = Airline::pluck('id', 'name')->all();
        $owners       = Owner::select('id', 'name','is_third_party')->get();
        $airports     = Airport::where('status', 1)->get();
        $data         = $purchase;
        $segments     = $purchase->segments ? json_decode($purchase->segments)[0]->legs : null;
        $baggage_info = $purchase->baggage_info ? json_decode($purchase->baggage_info, true) : [];

        if (!empty($baggage_info)) {
            $baggage_info['cabin_baggage'] = isset($baggage_info['cabin_baggage'])
                ? (int) filter_var($baggage_info['cabin_baggage'], FILTER_SANITIZE_NUMBER_INT)
                : null;

            $baggage_info['checkin_baggage'] = isset($baggage_info['checkin_baggage'])
                ? (int) filter_var($baggage_info['checkin_baggage'], FILTER_SANITIZE_NUMBER_INT)
                : null;
        }

        return view('flight-tickets.purchase.edit', compact('destinations', 'airlines', 'owners', 'data', 'airports', 'segments', 'baggage_info'));
    }

    public function updateAcknowledge(Request $request,$id){
        $requested_data = $request->session()->get('purchase_update_alert_'.$id);
        if($requested_data){
            $purchase = PurchaseEntry::find($id);
            $request->request->add($requested_data);
            return $this->_updateTheRecord($request,$purchase);
        }else{
            abort(403);
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\PurchaseEntry $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, \App\PurchaseEntry $purchase)
    {

        $this->validate($request, [
            'airline_id'     => 'required|integer',
            'destination_id' => 'required|integer',
            'pnr'            => 'required|unique:purchase_entries,pnr,' . $purchase->id,
            'flight_no'      => 'required',
            'travel_date'    => 'required|date',
            'name_list'      => 'required|date',
            'departure_time' => 'required',
            'arrival_time'   => 'required',
            'cost_price'     => 'required',
            'owner_id'       => 'required|integer',
            'flight_route'   => 'required',
            'sell_price'     => 'required',
            'infant'         => 'required'
            // 'tax' => 'required'
        ]);

        $modifed_key = [];

        if($request->airline_id != $purchase->airline_id){
            $old_airline = $purchase->airline->name;
            $new_airline = Airline::find($request->airline_id);
            array_push($modifed_key,'Airline from <b>'.$old_airline.'</b> to <b>'. $new_airline->name.'</b>');
        }


        if($request->destination_id != $purchase->destination_id){
            $old_destination = $purchase->destination->name;
            $new_destination = Destination::find($request->destination_id);
            array_push($modifed_key,'Sector from <b>'.$old_destination.'</b> to <b>'. $new_destination->name.'</b>');

        }


        if($request->pnr != $purchase->pnr){
            $old_destination = $purchase->destination->name;
            $new_destination = Destination::find($request->destination_id);
            array_push($modifed_key,'PNR from <b>'.$purchase->pnr.'</b> to <b>'. $request->pnr.'</b>');
        }

        $flight_no = ($request->airline_code .' '.$request->flight_no );

        if($flight_no != $purchase->flight_no) {
                array_push($modifed_key,'Flight No from <b>'.$purchase->flight_no .'</b> to <b>'. $flight_no .'</b>');

       }
       $travel_date  = Carbon::parse($request->travel_date)->format('d-m-Y');
       $old_travel_date = Carbon::parse($purchase->travel_date)->format('d-m-Y');
        if ($travel_date != $old_travel_date )
            array_push($modifed_key,'Travel Date modified from <b>'.$travel_date .'</b> to <b>'.$old_travel_date.'</b>');

        if($request->departure_time   != $purchase->departure_time)
            array_push($modifed_key,'Departure Time from <b>'.$purchase->departure_time . '</b> to <b>'. $request->departure_time.'</b>');

        if($request->arrival_time != $purchase->arrival_time)
            array_push($modifed_key,'Arrival Time from <b>'.$purchase->arrival_time . '</b> to <b>'. $request->arrival_time.'</b>');

        if($request->flight_route  != $purchase->flight_route)
        array_push($modifed_key,'Fligh Route from <b>'.$purchase->flight_route . '</b> to <b>'. $request->flight_route.'</b>');

        //return implode(',',$modifed_key);

        if(count($modifed_key) > 0) {
            $previous_booking = BookTicket::where('purchase_entry_id',$purchase->id);

            if($previous_booking->count() > 0){
                $request->session()->flash('purchase_update_alert_flash_'.$purchase->id,);
                $request->session()->put('purchase_update_alert_'.$purchase->id,$request->all());
                $data = $previous_booking->get();
                $purchase_entry_id = $purchase->id;
                return view('flight-tickets.purchase.update_alert',compact('data','purchase_entry_id','modifed_key'));
            }
        }

        return $this->_updateTheRecord($request,$purchase);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PurchaseEntry $purchaseEntry
     * @return \Illuminate\Http\Response
     */

    public function destroy($id, Request  $request)
    {
        $purchase = \App\PurchaseEntry::find($id);
        if ($purchase->sold == 0) {
            $purchase->delete();
            $request->session()->flash('success', 'Successfully Deleted');
            $query = $request->session()->get('searchQuery');
            if ($query) {
                $decoded_query = json_decode($query);
                $array = json_decode(json_encode($decoded_query), true);
                return redirect(route('purchase.index', $array));
            } else {
                return redirect(route('purchase.index'));
            }
        } else {
            // show error
            $request->session()->flash('error', 'Error in deleting the PurchaseEntry Entry');
            $query = $request->session()->get('searchQuery');
            if ($query) {
                $decoded_query = json_decode($query);
                $array = json_decode(json_encode($decoded_query), true);
                return redirect(route('purchase.index', $array));
            } else {
                return redirect(route('purchase.index'));
            }
        }
    }


    public function importExcel(Request $request)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 300); // 300 seconds = 5 minutes
        try{
            Excel::import(new TicketImport, $request->file('excel'));
            return redirect(route('purchase.index'))->with('success', 'Successfully Uploaded!');
        }catch(QueryException  $e) {
            return redirect(route('purchase.index'))->with('error', $e->getMessage());
        }catch(\Exception $e) {
            return redirect(route('purchase.index'))->with('error', $e->getMessage());
        }
    }



    private function _updateTheRecord($request,$purchase){
        $previous_qty  = $purchase->quantity;
        $available = $purchase->available;
        if($request->has('quantity'))
        {
            $sold          = $purchase->sold;
            $current_qty   = $request->quantity;

            if($sold > $current_qty){
                $request->session()->flash('error', 'New Quantity should be greater than no of sold ticket. Current Sold Ticket '.$sold);
                return redirect()->back();
            }

            if ($previous_qty > $current_qty) {
                $difference =  $previous_qty - $current_qty;
                $available  = $purchase->available - $difference;
            }
            if ($previous_qty < $current_qty) {
                $difference =  $current_qty - $previous_qty;
                $available  = $purchase->available + $difference;
            }
        }

        $airline = Airline::find($request->airline_id);
        $baggage_info = [
            'cabin_baggage'   => $request->cabin_baggage ? $request->cabin_baggage . 'KG' : null,
            'checkin_baggage' => $request->checkin_baggage ? $request->checkin_baggage . 'KG' : null,
            'cabin_baggage_count' => $request->cabin_baggage_count,
            'checkin_baggage_count' => $request->checkin_baggage_count,
        ];

        $leg1 = [];
        $leg2 = [];
        $duration = 0;

        if($request->flight_route == '1 Stop') {
            if($request->departure_time_2 != '' && $request->arrival_time_2 != '') {
                $departure_date_1 = date('Y-m-d', strtotime($request->travel_date_1));
                if(strtotime($request->departure_time_1) < strtotime($request->arrival_time_1)) {
                    $arrival_date_1 = date('Y-m-d', strtotime($request->travel_date_1));
                }else{
                    $arrival_date_1 = date('Y-m-d', strtotime($request->travel_date_1. ' + 1 days'));
                }

                $departure_date_2 = date('Y-m-d', strtotime($request->travel_date_2));
                if(strtotime($request->departure_time_2) < strtotime($request->arrival_time_2)) {
                    $arrival_date_2 = date('Y-m-d', strtotime($request->travel_date_2));
                }else{
                    $arrival_date_2 = date('Y-m-d', strtotime($request->travel_date_2. ' + 1 days'));
                }
                $arrival_date = $arrival_date_2;
                $destination = Destination::where('code', $request->source_id_1.$request->destination_id_2)->get();

                if(empty($destination)) {
                    $request->session()->flash('success', 'Destination is not present in database');
                    return redirect()->back();
                }

                $journey_time_1 = ((strtotime($arrival_date_1.' '.$request->arrival_time_1) - strtotime($request->travel_date_1.' '.$request->departure_time_1)) / 60);
                $journey_time_2 = ((strtotime($arrival_date_2.' '.$request->arrival_time_2) - strtotime($request->travel_date_2.' '.$request->departure_time_2)) / 60);

                $duration       = $journey_time_1 + $journey_time_2;
                $leg1 = [
                    "duration"       =>  $journey_time_1,
                    "departure_date" =>  $departure_date_1,
                    "departure_time" =>  $request->departure_time_1,
                    "arrival_time"   =>  $request->arrival_time_1,
                    "arrival_date"   =>  $arrival_date_1,
                    "airline_code"   =>  $airline->code,
                    "flight_number"  =>  $request->flight_no_1,
                    "origin"         =>  $request->source_id_1,
                    "destination"    =>  $request->destination_id_1
                ];

                $leg2 = [
                    "duration"       =>  $journey_time_2,
                    "departure_date" =>  $departure_date_2,
                    "departure_time" =>  $request->departure_time_2,
                    "arrival_time"   =>  $request->arrival_time_2,
                    "arrival_date"   =>  $arrival_date_2,
                    "airline_code"   =>  $airline->code,
                    "flight_number"  =>  $request->flight_no_2,
                    "origin"         =>  $request->source_id_2,
                    "destination"    =>  $request->destination_id_2
                ];
            }

            $legs = [];
            if(!empty($leg1)) {
                array_push($legs , $leg1);
            }

            if(!empty($leg2)) {
                array_push($legs , $leg2);
            }

            $segments = [
                [
                    "duration" => $duration,
                    "legs" => $legs
                ]
            ];
        }
        $flight_no =  trim($request->flight_no);
        $airline_code = trim($request->airline_code);


        $data = [
            'airline_id'     => $request->airline_id,
            'destination_id' => $request->destination_id,
            'pnr'            => trim($request->pnr),
            'flight_no'      => $airline_code.' '.$flight_no,
            'travel_date'    => Carbon::parse($request->travel_date),
            'name_list'      => Carbon::parse($request->name_list),
            'departure_time' => $request->departure_time,
            'arrival_time'   => $request->arrival_time,
            'available'      => $available,
            'child'          => $request->child,
            'infant'         => $request->infant,
            'cost_price'     => $request->cost_price,
            'sell_price'     => $request->sell_price,
            'owner_id'       => $request->owner_id,
            'base_price'     => $request->base_price,
            'tax'            => $request->tax,
            'arrival_date'   =>  Carbon::parse($request->arrival_date),
            'flight_route'   => $request->flight_route,
            'segments'       => isset($segments) ? json_encode($segments) : null,
            'baggage_info'   => json_encode($baggage_info),
        ];
        if($request->has('quantity')) {
            $data['quantity'] = $request->quantity;
        }

        $resp = $purchase->update($data);

        if ($resp) {
            $request->session()->flash('success', 'Successfully Update');
        } else
            $request->session()->flash('error', 'Opps something went wrong');

        $query = $request->session()->get('searchQuery');
        if ($query) {
            $decoded_query = json_decode($query);
            $array = json_decode(json_encode($decoded_query), true);
            return redirect(route('purchase.index', $array));
        } else {
            return redirect(route('purchase.index'));
        }
    }

    public function ViewstatusPage(Request $request, $purchase_entry_id)
    {
        $data = \App\PurchaseEntry::find($purchase_entry_id);
        $airlines = Airline::get();
        return view('flight-tickets.purchase.status', compact('data','airlines'));
    }


    public function submitStatusPage(Request $request)
    {

        //return $request->all();
        $flight_no =  trim($request->flight_no);
        $airline_code = trim($request->airline_code);

        $type =  $request->type;
        $data = \App\PurchaseEntry::find($request->purchase_entry_id);
        if ($type == 'irop') {
            // update purchase entry

            $data1 = [
                'travel_date' => Carbon::parse($request->travel_date),
                'departure_time' => $request->departure_time,
                'arrival_time' => $request->arrival_time,
                'arrival_date' => Carbon::parse($request->arrival_date),
                'flight_no' =>  $airline_code.' '.$flight_no,
                'flight_route' => $request->flight_route,
                'name_list' => Carbon::parse($request->name_list)
            ];



            $status =  $data->update($data1);


            $resp = PurchaseEntryStatus::Create([
                'purchase_entry_id' => $request->purchase_entry_id,
                'user_id' => Auth::user()->id,
                'data' => $data1,
                'remarks' => $request->remarks,
                'type' => 1,
            ]);
            // create a purchase entry status
        } else if ($type == 'cancel') {
            // update purchase entry isOnline to1
            $data->update([
                'isOnline' => 1
            ]);
            // create a purchase entry status
            $resp = PurchaseEntryStatus::Create([
                'user_id' => Auth::user()->id,
                'purchase_entry_id' => $request->purchase_entry_id,
                'remarks' => $request->remarks,
                'type' => 2,
            ]);
        } else if ($type == 'ontime') {
            $data1 = [
                'travel_date' => Carbon::parse($request->travel_date),
                'departure_time' => $request->departure_time,
                'arrival_time' => $request->arrival_time,
                'arrival_date' => Carbon::parse($request->arrival_date),
                'flight_no' => $airline_code.' '.$flight_no,
                'flight_route' => $request->flight_route,
                'name_list' => Carbon::parse($request->name_list)
            ];
            $status =  $data->update($data1);

            $resp = PurchaseEntryStatus::Create([
                'user_id' => Auth::user()->id,
                'purchase_entry_id' => $request->purchase_entry_id,
                'remarks' => $request->remarks,
                'type' => 3,
            ]);
        }
        $request->session()->flash('success', 'Modified the purchase entry status');
        return redirect(route('purchase.show', $request->purchase_entry_id));
    }


    public function updateIsOnline($id, Request $request)
    {
        $purchase =  \App\PurchaseEntry::find($id);
        $resp =  $purchase->update([
            'isOnline' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully Saved'
        ]);
    }



    public function updateIsRefundable($id, Request $request) {

        $purchase =  PurchaseEntry::find($id);
        $resp =  $purchase->update([
            'isRefundable' => $request->status
        ]);

        $ticketFareRules = RefundService::ticketHasFareRules($id);

        if($request->status == 1 && count($ticketFareRules) == 0) {
            RefundService::insertTicketFareRule($purchase);
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully Saved'
        ]);
    }


    public function bulkUpdateOfFlightNoArrDepTime(Request $request)
    {

        $purchase_entry_ids = $request->id;
        $arrival_time = $request->arrival_time;
        $departure_time = $request->departure_time;
        $base_price = $request->base_price;
        $tax = $request->tax;
        $cost_price = $request->cost_price;
        $flight_no = $request->flight_no;
        $infant_price = $request->infant_price;
        $owner_id = $request->owner_id;

        $data = [];
        if ($request->has('flight_no') && $request->flight_no != '')
            $data['flight_no']  = $flight_no;
        if ($request->has('arrival_time') && $request->arrival_time != '')
            $data['arrival_time'] = $arrival_time;
        if ($request->has('departure_time') && $request->departure_time != '')
            $data['departure_time'] = $departure_time;
        if ($request->has('base_price') && $request->base_price != '')
            $data['base_price'] = $base_price;
        if ($request->has('tax') && $request->tax != '')
            $data['tax'] = $tax;
        if ($request->has('cost_price') && $request->cost_price != '')
            $data['cost_price'] = $cost_price;
        if ($request->has('owner_id') && $request->owner_id != '')
            $data['owner_id'] = $owner_id;
        if ($request->has('infant_price') && $request->infant_price != '')
        $data['infant']  = $infant_price;



        foreach($purchase_entry_ids as $key => $id) {
            $purchase = \App\PurchaseEntry::find($id);
            $purchase->update($data);

            //Insert Into purchase entry status if flight status selected
            if ($request->has('flight_status') && $request->flight_status != '') {
                $user_id = Auth::id();
                $stats_data = [
                    'arrival_date'      => $purchase->arrival_date,
                    'arrival_time'      => isset($request->arrival_time) ? $request->arrival_time : $purchase->arrival_time,
                    'departure_time'    => isset($request->departure_time) ? $request->departure_time : $purchase->departure_time,
                    'travel_date'       => $purchase->travel_date,
                    'flight_no'         => isset($request->flight_no) ? $request->flight_no : $purchase->flight_no,
                    'flight_route'      => $purchase->flight_route,
                    'name_list'         => $purchase->name_list
                ];
                PurchaseEntryStatus::create([
                    'user_id'           =>   $user_id,
                    'purchase_entry_id' =>   $purchase->id,
                    'type'              =>   $request->flight_status,
                    'remarks'           =>   $request->flight_status == 1 ? 'IROP' : ($request->flight_status == 2 ? 'Cancelled' : 'Ontime'),
                    'data'              =>   $request->flight_status == 1 ? $stats_data : null
                ]);
            }
        }

        $request->session()->flash('success', 'Successfully Update');

        $query = $request->session()->get('searchQuery');

        if ($query) {
            $decoded_query = json_decode($query);
            $array = json_decode(json_encode($decoded_query), true);
            return redirect(route('purchase.index', $array));
        } else {
            return redirect(route('purchase.index'));
        }
    }


    public function submitSalePriceUpdate(Request  $request)
    {

        $purchase_entry_id = $request->id;
        $sale_price = $request->amount;
        $purchase_entry = \App\PurchaseEntry::find($purchase_entry_id);
        $old_sale_price = $purchase_entry->sell_price;
        $purchase_entry->update([
            'sell_price' => $sale_price,
            'child' => $sale_price,
        ]);

        activity('Flight Ticket Price Modified')
        ->performedOn($purchase_entry)
        ->event('updated')
        ->log( 'PNR '.$purchase_entry->pnr. ' Price has been modified from '.$old_sale_price.' to '.$sale_price);
        return response()->json([
            'success' => true,
            'message' => 'Successfully Updated'
        ]);
    }

    public function PurchaseEntryShouldBeOfflineList(){
        $current_date = Carbon::now();
        $datas = \App\PurchaseEntry::join('owners','owners.id','=','purchase_entries.owner_id')
            ->where('owners.is_third_party',1)
            ->where('purchase_entries.isOnline',2)
            ->where('purchase_entries.name_list_timestamp','<=',$current_date)
            ->orderBy('purchase_entries.travel_date','DESC')
            ->get();
        foreach($datas as $key => $val){
            $date = $this->get_namelist_timestamp($val->travel_date->format('Y-m-d'),$val->departure_time,$val->name_list_hour);

            $val->namelist_timestamp_expired =  $date->greaterThan(Carbon::now());
            $val->namelist_timestamp = $date->format('d-m-y H:i:s') ;
        }
        return view('flight-tickets.purchase.offline-tickets',compact('datas','current_date'));
    }

    public function PurchaseEntryShouldBeOfflineFormSubmit(Request $request){
        $form = $request->isForm;
        $current_date = Carbon::now();
        $datas = PurchaseEntry::join('owners','owners.id','=','purchase_entries.owner_id')
            ->where('owners.is_third_party',1)
            ->where('purchase_entries.isOnline',2)
            ->where('purchase_entries.name_list_timestamp','<=',$current_date)
            ->select('purchase_entries.id','purchase_entries.departure_time','purchase_entries.name_list_hour','purchase_entries.travel_date')
            ->get();

        foreach($datas as $key => $val){
            $date = $this->get_namelist_timestamp($val->travel_date->format('Y-m-d'),$val->departure_time,$val->name_list_hour);
            echo $val->travel_date->format('Y-m-d'),$val->departure_time,$val->name_list_hour;


            $namelist_timestamp_expired =  $date->greaterThan(Carbon::now());
//            echo $namelist_timestamp_expired;
//            dd($date);
            if(!$namelist_timestamp_expired){
                $val->update(['isOnline'=> 1]);
                echo $val->id;
                echo "<br>";
            }

        }

        if($form){
            $request->session()->flash('success','Successfully Updated');
            return redirect()->back();
        }else{
            return response()->json([
                'success' => true,
                'messsage' => 'Success',
                'data' => $datas
            ]);
        }

    }

    public function excel_pnr(Request  $request)
    {
        $data = new PurchaseEntryPNRExport($request);
        if ($data->collection()->isEmpty()) {
            return back()->with('error', 'Cannot export an empty data sheet.');
        }
        return Excel::download($data, 'ticket-pnr-export.xlsx');
    }

    public function get_namelist_timestamp($travel_date,$departure_time,$name_list_day)
    {
        $travel_time_stamp = $travel_date.' '.$departure_time;
        $namelist_timestamp = date('Y-m-d H:i:s', strtotime($travel_time_stamp . '-'.$name_list_day.' hours'));
        return Carbon::parse($namelist_timestamp);

    }

    public function apiOwnerStock(Request $request)
    {

        //$destinations = Destination::where('status', 1)->pluck('id', 'name')->all();
        $destinations = Destination::where('status', 1)->get();
        $airlines     = Airline::where('status', 1)->pluck('id', 'name')->all();
        $owners       = Owner::pluck('name', 'id')->all();
        $suppliers    = Owner::where('is_third_party', '=', 2)->get();
        $flight_no    = \App\PurchaseEntry::select('flight_no')->distinct()->get();

        $q  =  \App\PurchaseEntry::join('destinations as d', 'd.id', '=', 'purchase_entries.destination_id')
            ->join('airlines as a', 'a.id', '=', 'purchase_entries.airline_id')
            ->join('owners as o', 'o.id', '=', 'purchase_entries.owner_id')
            ->where('o.is_third_party', '=', '2')
            ->orderBy('purchase_entries.created_at', 'DESC');

        if ($request->has('destination_id') && $request->destination_id != '')
            $q->where('purchase_entries.destination_id', $request->destination_id);

        if ($request->has('entry_date') && $request->entry_date != '')
            $q->whereDate('purchase_entries.created_at', Carbon::parse($request->entry_date));


        if ($request->has('flight_no') && $request->flight_no != '')
            $q->where('purchase_entries.flight_no', $request->flight_no);

        if ($request->has('owner_id') && $request->owner_id != '')
            $q->where('purchase_entries.owner_id', $request->owner_id);

        if ($request->has('supplier_id') && $request->supplier_id != '')
            $q->where('purchase_entries.owner_id', $request->supplier_id);

        if ($request->has('pnr_no') && $request->pnr_no != '')
            $q->where('purchase_entries.pnr', $request->pnr_no);

        if ($request->has('travel_date_from') && $request->travel_date_from != '' && $request->has('travel_date_to') && $request->travel_date_to != '') {
            $from = Carbon::parse($request->travel_date_from);
            $to   = Carbon::parse($request->travel_date_to);
            $q->whereBetween('purchase_entries.travel_date', [$from, $to]);
        }

        if ($request->has('airline') && $request->airline != '')
            $q->where('purchase_entries.airline_id', $request->airline);

        if ($request->has('exclude_zero') && $request->exclude_zero != '')
            $q->where('purchase_entries.quantity', '>', 0);

        $data = $q
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
        $query = $request->all();

        $request->session()->put('searchQuery', json_encode($query));

        return view('flight-tickets.purchase.index-api', compact('suppliers', 'owners', 'data', 'destinations', 'airlines', 'flight_no'));
    }
    public function pnrReconciliation(){
        return view('flight-tickets.purchase.pnrReconciliation');
    }

    public function pnrReconciliationSubmit(Request  $request){
        $datas = $request->data;
        Log::info($datas);
        $previous_pnr = '';
        $results = [];
        foreach($datas as $key => $val){
            if($val['Passenger Name'] != NULL) {
                $currentPNR = $val['PNR'];
                if($previous_pnr ==  $currentPNR ){
                    $temp_array = [
                        'passenger_name' => $val['Passenger Name'],
                        'pax_type'   => explode('/',$val['Pax Type'])[0],
                        'gender'     => explode('/',$val['Pax Type'])[1]
                    ];
                    array_push($results[count($results)-1]['passengers'],$temp_array);
                    $results[count($results)-1]['pax_count']  += 1;


                }else{

                    $previous_pnr = $currentPNR;
                    $val['Travel Date'] = Carbon::parse($val['Travel Date'])->format('Y-m-d');
                    $val['passengers'] = [];
                    $val['pax_count'] = 1;
                    $val['passengers'][] = [
                        'passenger_name' => $val['Passenger Name'],
                        'pax_type'   => explode('/',$val['Pax Type'])[0],
                        'gender'     => explode('/',$val['Pax Type'])[1]
                    ];
                    $results[] = $val;
                }
            }


        }

        foreach($results as $i => $r){

            // TODO PNR Dynamics
            $purchase_entry = \App\PurchaseEntry::where('pnr',$r['PNR'])->first();
            $spiceJetPNR = SpiceJetPNRReconciliationSummary::where('purchase_entry_id',$purchase_entry->id)->first();
            if($spiceJetPNR)
            {
                $spiceJetPNR->update([
                    'total_pax_count' => $r['pax_count'],
                    'flight_no' => $r['Flight No'],
                    'travel_date'  => $r['Travel Date'],
                    'source' => $r['From'],
                    'destination'  => $r['To'],
                    'dep_time'  => $r['Dep Time'],
                    'arrival_time' => $r['Arr Time'],
                    'current_flight_status' => $r['Current Flight Status'],
                    'pnr_status' => $r['Current Flight Status']
                ]);
            }else{
                $temp_resp =  SpiceJetPNRReconciliationSummary::create([
                    'purchase_entry_id' => $purchase_entry->id ,
                    'total_pax_count' => $r['pax_count'],
                    'flight_no' => $r['Flight No'],
                    'travel_date'  => $r['Travel Date'],
                    'source' => $r['From'],
                    'destination'  => $r['To'],
                    'dep_time'  => $r['Dep Time'],
                    'arrival_time' => $r['Arr Time'],
                    'current_flight_status' => $r['Current Flight Status'],
                    'pnr_status' => $r['Current Flight Status'],
                ]);
            }
            $summary_log = SpiceJetPNRReconciliationSummaryLog::create(
                [
                    'purchase_entry_id' => $purchase_entry->id ,
                    'total_pax_count' => $r['pax_count'],
                    'flight_no' => $r['Flight No'],
                    'travel_date'  => $r['Travel Date'],
                    'source' => $r['From'],
                    'destination'  => $r['To'],
                    'dep_time'  => $r['Dep Time'],
                    'arrival_time' => $r['Arr Time'],
                    'current_flight_status' => $r['Current Flight Status'],
                    'pnr_status' => $r['Current Flight Status'],
                ]
            );
            if($spiceJetPNR) {
                SpiceJetPNRReconciliationDetail::where('spice_jet_p_n_r_reconciliation_summaries_id', $spiceJetPNR->id)->delete();
            }
            foreach($r['passengers'] as $x => $y)
            {
                $temp1 = [
                    'passenger_name' => $y['passenger_name'],
                    'pax_type'=> $y['pax_type'],
                    'gender'=> $y['gender'],
                ];
                $temp2 = [
                    'spice_jet_p_n_r_reconciliation_summary_logs_id' => $summary_log->id,
                    'passenger_name' => $y['passenger_name'],
                    'pax_type'=> $y['pax_type'],
                    'gender'=> $y['gender'],
                ];
                if($spiceJetPNR) {
                    $temp1['spice_jet_p_n_r_reconciliation_summaries_id'] = $spiceJetPNR->id;
                }else{
                    $temp1['spice_jet_p_n_r_reconciliation_summaries_id'] = $temp_resp->id;
                }
                SpiceJetPNRReconciliationDetail::create($temp1);
                SpiceJetPNRReconciliationDetailLog::create($temp2);
            }

        }


        return response()->json([
            'success' => true,
            'message' => 'Success'
        ]);
    }
}
