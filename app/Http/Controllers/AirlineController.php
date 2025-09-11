<?php

namespace App\Http\Controllers;


use App\Models\FlightTicket\Airline;
use App\Models\FlightTicket\AirlineCancellationPolicy;
use App\Models\FlightTicket\CancellationSlot;
use App\Models\FlightTicket\AirlineBaggageInfo;
use Illuminate\Support\Facades\Auth;
use App\Services\FlightService;
use Carbon\Carbon;
use App\PurchaseEntry;
use Illuminate\Http\Request;

class AirlineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct() {
        $this->middleware('permission:airline show', ['only' => ['index']]);
    }

    public function index(Request $request)
    {
        $query = Airline::query();
        if ($request->filled('code')) {
            $query->where('code', 'like', '%' . $request->code . '%');
        }
    
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $details = $query->orderBy('id', 'DESC')->simplePaginate(50);
        return view('settings.airlines.index', compact('details'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('settings.airlines.create');
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
            'name' => 'required|unique:airlines',
            'description' => 'required',
            'helpline_no' => 'required',
            'status' => 'required',
            'infant_charge' => 'required',

        ]);

        $details = [
            'name' => $request->name,
            'description' =>  $request->description,
            'status' =>  $request->status,
            'helpline_no' =>  $request->helpline_no,
            'infant_charge' => $request->infant_charge,
            'domestic' =>  1,
            'code' =>  $request->code,
        ];

        $resp = Airline::create($details);

        if ($resp)
            $request->session()->flash('success', 'Successfully Saved');
        else
            $request->session()->flash('error', 'Opps something went wrong');

        return redirect(route('airlines.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Airline  $airline
     * @return \Illuminate\Http\Response
     */
    public function show(Airline $airline)
    {
        $details = $airline;
        return view('settings.airlines.show', compact('details'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Airline  $airline
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $details = Airline::find($id);
        $slots = CancellationSlot::all();
        $cancellation = AirlineCancellationPolicy::where('airline_id', $id)->get();
        $baggageInfo = AirlineBaggageInfo::where('airline_code', $details->code)->get();
        return view('settings.airlines.edit', compact('details','slots','cancellation','baggageInfo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Airline  $airline
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $airline = Airline::find($id);

        $this->validate($request, [
            'name' => 'required|unique:airlines,name,' . $airline->id,
            'description' => 'required',
            'helpline_no' => 'required',
            'infant_charge' => 'required|integer',
            'status' => 'required',

        ]);

        $details = [
            'name' => $request->name,
            'description' =>  $request->description,
            'status' =>  $request->status,
            'domestic' =>  1,
            'infant_charge' => $request->infant_charge,
            'helpline_no' =>  $request->helpline_no,
            'code' =>  $request->code,
        ];


        if($request->infant_charge_modify == "1"
         &&
          $request->infant_charge != $airline->infant_charge)
            {
            $resp  = PurchaseEntry::where('airline_id',$airline->id)
                ->whereDate('travel_date','>=', Carbon::now())->update([
                    "infant" => $request->infant_charge
                ]);


            }
       $resp = $airline->update($details);

        if ($resp)
            $request->session()->flash('success', 'Successfully Saved');
        else
            $request->session()->flash('error', 'Opps something went wrong');

        return redirect(route('airlines.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Airline  $airline
     * @return \Illuminate\Http\Response
     */
    public function destroy(Airline $airline)
    {
        //
    }


    public function updateCancellationPolicy(Request $request, $id) {

        $slots = CancellationSlot::all();

        foreach($slots as $key => $slot) {
            AirlineCancellationPolicy::updateOrCreate(
                ['airline_id' => $id, 'slot_id' => $slot->id],
                [
                    'airline_id' => $id,
                    'slot_id' => $slot->id,
                    'amount' => $request->cancellation_slot_amount[$key],
                    'int_amount' => $request->cancellation_slot_int_amount[$key]
                ]
            );
        }

        $request->session()->flash('success', 'Successfully Saved Cancellation Slots');

        return redirect(route('airlines.index'));
    }


    public function updateBaggageInfo(Request $request, $id) {

        $this->validate($request, [
            'cabin_baggage_adult_domestic' => 'required',
            'cabin_baggage_child_domestic' => 'required',
            'cabin_baggage_infant_domestic' => 'required',
            'cabin_baggage_adult_international' => 'required',
            'cabin_baggage_child_international' => 'required',
            'cabin_baggage_infant_international' => 'required',
            'checkin_baggage_adult_domestic' => 'required',
            'checkin_baggage_child_domestic' => 'required',
            'checkin_baggage_infant_domestic' => 'required',
            'checkin_baggage_adult_international' => 'required',
            'checkin_baggage_child_international' => 'required',
            'checkin_baggage_infant_international' => 'required',
        ]);


        $airline = Airline::find($id);

        AirlineBaggageInfo::updateOrCreate(
            ['airline_code' => $airline->code, 'type' => 'Cabin  Baggage','mode' => 'domestic'],
            [
                'airline_code' => $airline->code,
                'type'  => 'Cabin  Baggage',
                'adult' => $request->cabin_baggage_adult_domestic,
                'child' => $request->cabin_baggage_child_domestic,
                'infant' => $request->cabin_baggage_infant_domestic,
                'mode'   => 'domestic',
                'user_id' => Auth::user()->id
            ]
        );

        AirlineBaggageInfo::updateOrCreate(
            ['airline_code' => $airline->code, 'type' => 'Cabin  Baggage','mode' => 'international'],
            [
                'airline_code' => $airline->code,
                'type'  => 'Cabin  Baggage',
                'adult' => $request->cabin_baggage_adult_international,
                'child' => $request->cabin_baggage_child_international,
                'infant' => $request->cabin_baggage_infant_international,
                'mode'   => 'international',
                'user_id' => Auth::user()->id
            ]
        );

        AirlineBaggageInfo::updateOrCreate(
            ['airline_code' => $airline->code , 'type' => 'Check-in Baggage','mode' => 'domestic'],
            [
                'airline_code' => $airline->code,
                'type'  => 'Check-in Baggage',
                'adult' => $request->checkin_baggage_adult_domestic,
                'child' => $request->checkin_baggage_child_domestic,
                'infant' => $request->checkin_baggage_infant_domestic,
                'mode'   => 'domestic',
                'user_id' => Auth::user()->id
            ]
        );

        AirlineBaggageInfo::updateOrCreate(
            ['airline_code' => $airline->code , 'type' => 'Check-in Baggage','mode' => 'international'],
            [
                'airline_code' => $airline->code,
                'type'  => 'Check-in Baggage',
                'adult' => $request->checkin_baggage_adult_international,
                'child' => $request->checkin_baggage_child_international,
                'infant' => $request->checkin_baggage_infant_international,
                'mode'   => 'international',
                'user_id' => Auth::user()->id
            ]
        );

        if($request->update_ticket_baggage_info) {
            $baggage_info = FlightService::getBaggageInfo($airline->code);
            PurchaseEntry::where('airline_id',$airline->id)
            ->whereDate('travel_date','>=', Carbon::now())->update([
                "baggage_info" => json_encode($baggage_info)
            ]);
        }

        $request->session()->flash('success', 'Successfully Saved Baggage Information');

        return redirect(route('airlines.index'));
    }
}
