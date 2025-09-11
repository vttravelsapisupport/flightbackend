<?php

namespace App\Http\Controllers;

use App\Models\FlightTicket\Airline;
use App\Models\FlightTicket\AirlineSectorBaggageInfo;
use App\Models\NameListManagerAlignment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\FlightTicket\Airport;
use App\Models\FlightTicket\Destination;
use App\Services\FlightService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\PurchaseEntry;
use App\Models\FlightTicket\FlightInventorySummarySectorManager;

class DestinationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct() {
        $this->middleware('permission:destination show', ['only' => ['index']]);
    }

    public function index(Request  $request)
    {
        $q = Destination::orderBy('id','DESC');
        if($request->has('name') && $request->name != ''){
            $q->where('code','like','%'.strtolower($request->name).'%');
        }

        if($request->has('status') && $request->status != ''){
            $status = ($request->status == 2) ? 0 :$request->status;
            $q->where('status','=',$status);
        }

        if($request->has('revenue_manager_id') && $request->revenue_manager_id != ''){
            $sectors =   FlightInventorySummarySectorManager::where('manager_id',$request->revenue_manager_id)->pluck('sector_id');
           
            if(count($sectors) > 0)
            $q->whereIn('id',$sectors);
        }

        $details = $q->simplePaginate(50);

        $revenue_managers =  User::join('model_has_roles','model_has_roles.model_id','=','users.id')
        ->join('roles','model_has_roles.role_id','=','roles.id')
        ->whereIn('roles.id',[1,2,3,9])
        ->pluck('users.first_name as name','users.id as id',);


        return view('settings.destinations.index',compact('details','revenue_managers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $airports =Airport::where('status',1)->get();
        return view('settings.destinations.create',compact('airports'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|unique:destinations',
            'code' => 'required|unique:destinations',
            'status' => 'required',
            'destination_id' => [
                'required',
                Rule::unique('destinations')->where(function ($query) use ($request) {
                    return $query->where('origin_id', $request->origin_id);
                }),
                'different:origin_id'
            ],
            'origin_id' => 'required|different:destination_id',
        ]);

        $details =[
            'name' => $request->name,
            'code' => $request->code,
            'status' => $request->status,
            'is_international' => 0,
            'destination_id' => $request->destination_id,
            'origin_id' => $request->origin_id,
        ];

        $resp = Destination::create($details);

        if($resp)
            $request->session()->flash('success','Successfully Saved');
        else
            $request->session()->flash('error','Opps something went wrong');

        return redirect(route('destinations.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $details = Destination::find($id);

        return view('settings.destinations.show',compact('details'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $details  = Destination::find($id);
        $airports = Airport::where('status', 1)->get();
        $airlines = Airline::where('status', 1)->get();
        $name_list_managers = NameListManagerAlignment::where('sector_id',$id)->get();
        $managers =   User::join('model_has_roles','model_has_roles.model_id','=','users.id')
                ->join('roles','model_has_roles.role_id','=','roles.id')
                ->whereIn('roles.id',[1,2,3,9])
                ->select('users.id','users.first_name','users.last_name','users.phone','users.email','roles.name')
                ->get();
        $selected_manager =   FlightInventorySummarySectorManager::where('sector_id',$details->id)->first();

        return view('settings.destinations.edit',compact('details','airports','managers','selected_manager','airlines','name_list_managers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $destination = Destination::find($id);

        $this->validate($request,[
            'name' => 'required|unique:destinations,name,'.$destination->id,
            'code' => 'required|unique:destinations,code,'.$destination->id,
            'status' => 'required',
            'destination_id' => 'required',
            'origin_id' => 'required',
        ]);

      
        $details =[
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'status' => $request->status,
            'is_international' => $request->is_international ? 1 : 0,
            'destination_id' => $request->destination_id,
            'origin_id' => $request->origin_id,
        ];

        $resp = $destination->update($details);
       
        if($request->has('manager_id') && $request->manager_id !== null){
            FlightInventorySummarySectorManager::updateOrCreate(
                [ 'sector_id' =>  $destination->id],
                [ 'manager_id' => $request->manager_id]
            );
        }
        // name_list_airline_id
        // name_list_manager
        $name_list_airline_id = $request->name_list_airline_id;

        foreach($name_list_airline_id as $k => $v){
                $airline_id = $v;
                $user_id = $request->name_list_manager[$k];
                $sector_id = $id;

                NameListManagerAlignment::updateOrCreate(
                        ['airline_id' => $airline_id,'sector_id' => $sector_id],
                        ['user_id' => $user_id]
                );
        }

        if($resp)
            $request->session()->flash('success','Successfully Updated');
        else
            $request->session()->flash('error','Opps something went wrong');

        return redirect(route('destinations.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function destroy(Destination $destination)
    {
        //
    }


    public function updateBaggageInfo(Request $request, $id) {

        $this->validate($request, [
            'airline_id'  => 'required',
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

        $airline = Airline::find($request->airline_id);
        $destination = Destination::find($id);

        AirlineSectorBaggageInfo::updateOrCreate(
            [
                'airline_code' => $airline->code, 
                'sector_code'  => $destination->code,
                'type' => 'Cabin  Baggage',
                'mode' => 'domestic'
            ],
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

        AirlineSectorBaggageInfo::updateOrCreate(
            [
                'airline_code' => $airline->code, 
                'sector_code'  => $destination->code,
                'type' => 'Cabin  Baggage',
                'mode' => 'international'
            ],
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

        AirlineSectorBaggageInfo::updateOrCreate(
            [
                'airline_code' => $airline->code , 
                'sector_code'  => $destination->code,
                'type' => 'Check-in Baggage',
                'mode' => 'domestic'
            ],
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

        AirlineSectorBaggageInfo::updateOrCreate(
            [
                'airline_code' => $airline->code, 
                'sector_code'  => $destination->code,
                'type' => 'Check-in Baggage',
                'mode' => 'international'
            ],
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

        
        $baggage_info = FlightService::getSectorAirlineBaggageInfo($airline->code, $destination->code);
        PurchaseEntry::where('airline_id', $airline->id)
        ->where('destination_id', $destination->id)
        ->whereDate('travel_date','>=', Carbon::now())->update([
            "baggage_info" => json_encode($baggage_info)
        ]);
  
        $request->session()->flash('success', 'Successfully Saved Baggage Information');

        return redirect(route('destinations.index'));
    }
}
