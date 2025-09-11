<?php

namespace App\Http\Controllers;

use App\Models\FlightTicket\Airport;
use Carbon\Carbon;
use App\Models\FlightTicket\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MailerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:mailer-notification show', ['only' => ['index']]);
    }

    public function sendMail(Request $request) {
        $output_datas = json_decode($request->input('output_datas'));
        // TODO: instead of returning the view we simply have to pass it to our emailer class
        return view('emails.notifications.mailer', compact('output_datas'));
    }

    public function index(Request   $request)
    {
        $search         = $request->search;
        $destinations_id = $request->destination_id;
       // dd($destinations_id);
        $ex             = Airport::where('status', 1)->get();
        $destinations   = Destination::where('origin_id',$request->ex) ->orWhere('destination_id', '=', $request->ex)->pluck('name', 'id')->all();
        $output_datas = '';

        if ($search) {
            $days = $request->days;
            $sectors = Destination::whereIn('id', $destinations_id)->where('status', 1)->get();
            // dd($sectors);
            $output_datas = [];
            

            foreach ($sectors as $i => $sector) {
                $date =  Carbon::now();

                $purchase_entry = DB::table('purchase_entries')
                     ->join('destinations', 'destinations.id','=','purchase_entries.destination_id')
                     ->join('airports as d', 'd.id','=','destinations.destination_id')
                     ->join('airports as o', 'o.id','=','destinations.origin_id')
                    ->where('purchase_entries.destination_id', DB::raw($sector->id))
                    ->where('purchase_entries.available', '>', DB::raw(1))
                    ->where('purchase_entries.isOnline', '=', DB::raw(2))
                    ->whereDate('purchase_entries.travel_date', '>', DB::raw("'$date'"))
                    ->groupBy('purchase_entries.travel_date')
                    ->orderBy('purchase_entries.travel_date', 'ASC')
                    ->select('purchase_entries.destination_id', 'purchase_entries.travel_date', DB::raw('min(purchase_entries.sell_price) as sell_price'),'d.code as destination_code','o.code as origin_code')
                    ->limit($days)
                    ->get()->toArray();  
                
                if (count($purchase_entry) == 0) continue;

                $output_datas[$sector->name] = $purchase_entry;
            }
        }

        return view('notifications.mailer.index', compact('ex',  'output_datas', 'destinations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        //
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

    public function getDestinationBasedOnEx(Request $request, $id)
    {
        return  Destination::where('origin_id', '=', $id)
           ->orWhere('destination_id', '=', $id)
           ->orderByRaw("CASE WHEN origin_id = $id THEN 0 ELSE 1 END ASC")
           ->orderBy('name', 'ASC')
            ->get();
    }

    public function getAirportBasedOnEx(Request $request, $id)
    {
        
        $destinations = $this->getDestinationBasedOnEx($request, $id);

        $originIds = $destinations->pluck('origin_id')->toArray();
        $destinationIds = $destinations->pluck('destination_id')->toArray();

        return DB::table('airports')
                        ->whereIn('id', $originIds)
                        ->orWhereIn('id', $destinationIds)
                        ->where('status', '=', 1)
                        ->select('id','code', 'name', 'city')
                        ->distinct()
                        ->get();

          
    }

  
}
