<?php

namespace App\Http\Controllers\FlightTicket\Reports;

use App\User;
use App\PurchaseEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\FlightTicket\Owner;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\FlightTicket\Airline;
use App\Models\FlightTicket\FlightInventorySummarySectorManager;

class FlightInventorySummary extends Controller
{
    public function index(Request $request)
    {

        ini_set('memory_limit', '256M');
        $owners   =   Owner::select('name','id','is_third_party')->where('status', 1)->get();
        $airlines = Airline::where('status', 1)->pluck('name', 'id')->all();
        $own_vendors =    $owners->where('is_third_party',0)->pluck('name', 'id')->all();
        $third_party_vendors   =   $owners->where('is_third_party',1)->pluck('name', 'id')->all();

        $users    =   User::join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->whereIn('roles.id', [1, 2, 3, 9])
            ->select('users.id', 'users.first_name', 'users.last_name')
            // ->pluck('users.name','users.id')
            ->get();

        // TODO: 1hr redis caching has to be added
        // TODO: check query execution time
        // TODO: add pagination
        // TODO: add permission


        if (isset($request->start_date) && isset($request->end_date)) {
            $start_date = Carbon::parse($request->start_date);
            $end_date = Carbon::parse($request->end_date);
            if($start_date->format('Y-m-d') <= '2023-03-31' ||
                $end_date->format('Y-m-d') <= '2023-03-31'
            ){
                $datas = collect([]);
                return view('flight-inventory-summary.index', compact('airlines', 'own_vendors','third_party_vendors', 'users'));
            }
            $diff_in_days = $start_date->diffInDays($end_date);



            // dd($start_date->startOfDay());
            // dd($end_date->endOfDay());
            if ($diff_in_days < 31)
            {
                $q = PurchaseEntry::join('destinations', 'purchase_entries.destination_id', '=', 'destinations.id')
                    ->leftJoin(
                        'flight_inventory_summary_sector_managers',
                        'flight_inventory_summary_sector_managers.sector_id',
                        '=',
                        'destinations.id'
                    )
                    ->leftJoin(
                        'users',
                        'users.id',
                        '=',
                        'flight_inventory_summary_sector_managers.manager_id'
                    )
                    ->select(
                        DB::raw("SUM(quantity) as total_quantity"),
                        'purchase_entries.*',
                        'users.first_name',
                        'users.last_name',
                        DB::raw('SUM(purchase_entries.available) AS total_available'),
                        DB::raw('(SUM(purchase_entries.available)  *  cost_price) AS total_inventory_cost '),
                        DB::raw('SUM(purchase_entries.sold) AS total_sold'),
                        DB::raw('SUM(purchase_entries.blocks) AS total_block')
                    )
                    ->whereBetween('purchase_entries.travel_date', [$start_date->startOfDay(), $end_date->endOfDay()])
                    ->groupBy('purchase_entries.destination_id', 'purchase_entries.owner_id', 'purchase_entries.flight_no')
                    ->orderBy('destinations.name', 'ASC');


                if ($request->has('airline_id')  && $request->airline_iud != '') {
                    $q->where('purchase_entries.airline_id', '=', $request->airline_id);
                }

                if ($request->has('exclude_zero')  && $request->exclude_zero != '') {
                    $q->whereRaw("purchase_entries.quantity  > 0");
                }

                if ($request->has('unassigned_sector') && $request->unassigned_sector !='') {
                    $q->whereNull('flight_inventory_summary_sector_managers.sector_id') ;
                }


                if ($request->has('own_vendor_id') || $request->has('third_party_vendor_id'))
                {
                    if($request->has('own_vendor_id') && $request->has('third_party_vendor_id')){
                        $array = array_merge($request->third_party_vendor_id,$request->own_vendor_id);
                        $q->whereIn('purchase_entries.owner_id', $array);
                    }elseif($request->has('own_vendor_id')){
                        $q->whereIn('purchase_entries.owner_id', $request->own_vendor_id);
                    }elseif($request->has('third_party_vendor_id')){
                        $q->whereIn('purchase_entries.owner_id', $request->third_party_vendor_id);
                    }
                }

                // if ($request->has('own_vendor_id') || $request->has('third_party_vendor_id')) {
                //     $selectedOwners = [];

                //     if ($request->has('own_vendor_id')) {
                //         $selectedOwners = $request->input('own_vendor_id');
                //     }

                //     if ($request->has('third_party_vendor_id')) {
                //         $selectedOwners = array_merge($selectedOwners, $request->input('third_party_vendor_id'));
                //     }

                //     $q->whereIn('purchase_entries.owner_id', $selectedOwners);
                // }


                if ($request->has('manager_id') && $request->manager_id != '') {
                    $t =  FlightInventorySummarySectorManager::where('manager_id', $request->manager_id)->pluck('sector_id');
                    if ($t) {
                        $q->whereIn('purchase_entries.destination_id',$t);
                    }
                }
                $details = $q->simplePaginate(50);

                return view('flight-inventory-summary.index', compact('details', 'airlines', 'own_vendors','third_party_vendors', 'users'));
            } else {
                redirect()->back()->with('error', 'Cannot process more than 31 days of records.');
            }
        }
        return view('flight-inventory-summary.index', compact('airlines', 'own_vendors','third_party_vendors', 'users'));
    }
}
