<?php
namespace App\Http\Controllers\FlightTicket;

use App\Http\Controllers\Controller;
use App\Models\FlightTicket\Owner;
use App\Models\FlightTicket\Airline;
use Carbon\Carbon;
use App\Models\FlightTicket\Destination;
use App\PurchaseEntry;
use Illuminate\Http\Request;
use App\Models\FlightTicket\PurchaseTicketFareLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FareManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request)
    {
        // $destinations = Destination::where('status', 1)->pluck('id', 'name')->all();
        $flight_no = PurchaseEntry::select('flight_no')->distinct()->get();
        $destinations = Destination::where('status', 1)->get();
        $airlines     = Airline::where('status', 1)->pluck('id', 'name')->all();
        $owners       = Owner::pluck('name', 'id')->all();
        $suppliers    = Owner::where('is_third_party', '=', 1)->get();


        $q = PurchaseEntry::join('destinations as d', 'd.id', '=', 'purchase_entries.destination_id')
                            ->join('airlines as a', 'a.id', '=', 'purchase_entries.airline_id')
                            ->join('owners as o', 'o.id', '=', 'purchase_entries.owner_id')
                            ->orderBy('travel_date', 'ASC');

        if ($request->has('owner_id') && $request->owner_id != '')
        $q->where('purchase_entries.owner_id', $request->owner_id);

        if ($request->has('supplier_id') && $request->supplier_id != '')
            $q->where('purchase_entries.owner_id', $request->supplier_id);

        if ($request->has('destination_id') && $request->destination_id != '')
            $q->where('purchase_entries.destination_id', $request->destination_id);
        if ($request->has('pnr_no') && $request->pnr_no != '')
            $q->where('purchase_entries.pnr', $request->pnr_no);
        if ($request->has('travel_date_from') && $request->travel_date_from != '' && $request->has('travel_date_to') && $request->travel_date_to != '') {
            $from = Carbon::parse($request->travel_date_from);
            $to   = Carbon::parse($request->travel_date_to);
            $q->whereBetween('purchase_entries.travel_date', [$from, $to]);

            if($from->format('Y-m-d') <= '2023-03-31' ||
                $to->format('Y-m-d') <= '2023-03-31'
            ){
                $datas = collect([]);
                return view('flight-tickets.fare-management.index', compact('datas', 'destinations', 'airlines','owners','suppliers','flight_no'));
            }

        }else{
            $q->whereDate('purchase_entries.travel_date', '>=','2023-04-01');
        }
        if ($request->has('airline') && $request->airline != '')
            $q->where('purchase_entries.airline_id', $request->airline);

        if ($request->has('flight_no') && $request->flight_no != '')
            $q->where('purchase_entries.flight_no', $request->flight_no);

        if ($request->has('exclude_zero') && $request->exclude_zero != '') {
            $q->where(function ($query) {
                $query->where('purchase_entries.available', '>', 0)
                    ->orWhere('purchase_entries.blocks', '>', 0 );
            });
        }
        if ($request->has('namelist_status_id') && $request->namelist_status_id != ''){
            $name_list_id = $request->namelist_status_id;
            if($name_list_id == 4){
                $name_list_id = 0;
            }
            $q->where('purchase_entries.namelist_status', $name_list_id);
        }


        if ($request->has('namelist_date') && $request->namelist_date != '') {
            $namelist_date = Carbon::parse($request->namelist_date);
            $q->whereDate('purchase_entries.name_list', $namelist_date);
        }


        if ($request->has('type') && $request->type != '') {
            $q->where('purchase_entries.isOnline', $request->type);
        }

        if ($request->has('over_booking') && $request->over_booking != '') {
            $q->where('purchase_entries.quantity','<','purchase_entries.sold');

        }
        if ($request->has('show_zero') && $request->show_zero != '') {
            $q->where(function ($query) {
                $query->where('purchase_entries.available',  0)
                    ->Where('purchase_entries.blocks',0);
            });
        }
        // $q->where('blocks', '>', 0);

        $datas = $q->select('a.name as airline_name',
        'purchase_entries.namelist_status',
        'purchase_entries.isRefundable',
        'purchase_entries.airline_id',
        'purchase_entries.flight_no','d.name as destination_name','purchase_entries.pnr','purchase_entries.available', 'purchase_entries.quantity', 'purchase_entries.id','purchase_entries.blocks','purchase_entries.cost_price','purchase_entries.sell_price','purchase_entries.isOnline','purchase_entries.travel_date','purchase_entries.departure_time','purchase_entries.arrival_time','purchase_entries.name_list','o.name as owner_name','o.is_third_party as owner_type','purchase_entries.flight_route'
        ,DB::raw('(select type from purchase_entry_statuses where purchase_entry_id  =   purchase_entries.id   order by id desc limit 1) as FlightStatus'))
                        ->simplePaginate(50);

        return view('flight-tickets.fare-management.index', compact('datas', 'destinations', 'airlines','owners','suppliers','flight_no'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('flight-tickets.fare-management.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        // return $request->all();
        $type   = $request->type;
        $status = $request->status;
        $cost_price = $request->cost_price;


        if ($type) {
            if ($type == '1') {     //fixed
                $price = $request->price;
                if($price < 1000) {
                    $request->session()->flash('error', 'Cannot update fixed price of 3 digit figure');
                    return redirect()->back();
                }
                $request->session()->put('type', $type);
                $request->session()->put('price', 'value');
                foreach ($request->new_price as $key => $val) {
                    //create history
                    $purchase_entry_id = $request->purchase_entry_id[$key];
                    // update the  purchase entry date with current price
                    $history_data = [
                        'new_price' => $price,
                        'purchase_entry_id' => $purchase_entry_id,
                        'owner_id' => Auth::id()
                    ];
                    PurchaseTicketFareLog::create($history_data);
                    $purchase_entry = PurchaseEntry::find($purchase_entry_id);
                    $purchase_entry->update([
                        'sell_price' => $price,
                        'child' => $price
                    ]);
                }
            } elseif ($type == '2') {  //markup
                $price = $request->price;
                foreach ($request->new_price as $key => $val) {
                    $purchase_entry_id = $request->purchase_entry_id[$key];
                    $cost_price = $request->cost_price[$key];
                    $new_sell_price = $cost_price + $price;
                    // update the  purchase entry date with current price
                    $history_data = [
                        'new_price' => $new_sell_price,
                        'purchase_entry_id' => $purchase_entry_id,
                        'owner_id' => Auth::id()
                    ];
                    PurchaseTicketFareLog::create($history_data);
                    $purchase_entry = PurchaseEntry::find($purchase_entry_id);
                    $purchase_entry->update([
                        'sell_price' => $new_sell_price,
                        'child' => $new_sell_price
                    ]);
                }
            }
            $request->session()->flash('success', 'Successfully updated the price');
        } elseif ($status) {
            foreach ($request->mode as $key => $val) {
                //create history
                $purchase_entry_id = $request->purchase_entry_id[$key];
                $purchase_entry = PurchaseEntry::find($purchase_entry_id);
                $purchase_entry->update([
                    'isOnline' => $status
                ]);
            }
            $request->session()->flash('success', 'Successfully updated the status');
        }else {

            foreach ($request->new_price as $key => $val) {
                if (!empty($val)) {
                    //create history
                    if($val < 1000) {
                        continue;
                    }
                    $purchase_entry_id = $request->purchase_entry_id[$key];
                    $new_price = $val;

                    $history_data = [
                        'new_price' => $new_price,
                        'purchase_entry_id' => $purchase_entry_id,
                        'owner_id' => Auth::id()
                    ];
                    PurchaseTicketFareLog::create($history_data);
                    $purchase_entry = PurchaseEntry::find($purchase_entry_id);
                    $purchase_entry->update([
                        'sell_price' => $new_price,
                        'child' => $new_price
                    ]);
                }
            }

            foreach ($request->mode as $key => $val) {
                if (!empty($val)) {
                    $purchase_entry_id = $request->purchase_entry_id[$key];
                    $new_price = $val;
                    $purchase_entry = PurchaseEntry::find($purchase_entry_id);
                    $purchase_entry->update([
                        'isOnline' => $val
                    ]);
                }
            }
        }






        return redirect()->back();
        // return redirect(route('fare-management.index'));
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
