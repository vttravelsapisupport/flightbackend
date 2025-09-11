<?php

namespace App\Http\Controllers;

use App\Models\AirportAlignment;
use App\Models\FlightTicket\Airport;
use Illuminate\Http\Request;

class AirportController extends Controller
{

    public function __construct() {
        $this->middleware('permission:airport show', ['only' => ['index']]);
    }

    public function index(Request  $request) {
        $q = Airport::orderBy('id','DESC');
        if($request->has('name') && $request->name != ''){
            $q->where('name','like','%'.strtolower($request->name).'%');
        }

        if($request->has('status') && $request->status != ''){
            $status = ($request->status == 2) ? 0 : $request->status;
            $q->where('status','=',$status);
        }
        $details = $q->simplePaginate(50);
        return view('settings.airports.index',compact('details'));
    }


    public function create()
    {
        $airports = Airport::where('status',1)->get();
        return view('settings.airports.create',compact('airports'));
    }


    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'code' => 'required',
            'status' => 'required',
            'city_name' => 'required',
            'city_code' =>  'required',
            'country_name' =>  'required',
            'country_code' =>  'required',
            'timezone' =>  'required',
            'latitude' =>  'required',
            'longitude' =>  'required',
            'airport_number' =>  'required',
        ]);

        $details =[
            'name' => $request->name,
            'code' => $request->code,
            'cityName' => $request->city_name,
            'cityCode' => $request->city_code,
            'countryName' => $request->country_name,
            'countryCode' => $request->country_code,
            'timezone' => $request->timezone,
            'lat' => $request->latitude,
            'lon' => $request->longitude,
            'numAirports' => $request->airport_number,
            'city' => isset($request->is_city) ? true : '',
            'status' => $request->status,
        ];

        $resp = Airport::create($details);

        if($resp)
            $request->session()->flash('success','Successfully Saved');
        else
            $request->session()->flash('error','Opps something went wrong');

        return redirect(route('airports.index'));
    }


    public function show($id)
    {
        $details = Airport::find($id);

        return view('settings.airports.show',compact('details'));

    }


    public function edit($id)
    {
        $details = Airport::find($id);

        $airports = Airport::where('countryName','INDIA')->where('status', 1)->get();
        return view('settings.airports.edit',compact('details','airports'));
    }



    public function update(Request $request)
    {

        $this->validate($request,[
            'name' => 'required',
            'code' => 'required',
            'status' => 'required',
            'city_name' => 'required',
            'city_code' =>  'required',
            'country_name' =>  'required',
            'country_code' =>  'required',
            'timezone' =>  'required',
            'latitude' =>  'required',
            'longitude' =>  'required',
            'airport_number' =>  'required',
        ]);

        $details =[
            'name' => $request->name,
            'code' => $request->code,
            'cityName' => $request->city_name,
            'cityCode' => $request->city_code,
            'countryName' => $request->country_name,
            'countryCode' => $request->country_code,
            'timezone' => $request->timezone,
            'lat' => $request->latitude,
            'lon' => $request->longitude,
            'numAirports' => $request->airport_number,
            'city' => isset($request->is_city) ? true : '',
            'status' => $request->status,
        ];

        $airport = Airport::find($request->id);

        $resp = $airport->update($details);

        if($resp)
            $request->session()->flash('success','Successfully Updated');
        else
            $request->session()->flash('error','Opps something went wrong');

        return redirect(route('airports.index'));
    }

    public function showAirportAligmnetPage(Request $request){


        $q = AirportAlignment::orderBy('id','DESC');

        if($request->has('airport_code') && $request->airport_code != ''){
            $q->where('airport_code',$request->airport_code);
        }

        if($request->has('status') && $request->status != ''){
            $status = ($request->status == 2) ? 0 : $request->status;
            $q->where('status','=',$status);
        }
        $airports = $q->simplePaginate(50);

        return view('settings.airports.airport_alignment',compact('airports'));
    }

    public function airportAlignmentSubmit(Request $request){


       $this->validate($request,[
            'airport_code' => 'required',
            'airport_align' => 'required'
       ]);

       $data = [
            'airport_code' => $request->airport_code,
            'airport_align' => $request->airport_align,
            'status' => 1
       ];

       $data1 = [
            'airport_code' => $request->airport_align,
            'airport_align' => $request->airport_code,
            'status' => 1
       ];

       $airport  = AirportAlignment::create($data);
       $airport1 = AirportAlignment::create($data1);

       $request->session()->flash('success','Successfully Saved');
       return redirect()->back();
    }

    public function updateIsActive($id, Request $request){
        $airport = AirportAlignment::find($id);
        $resp = $airport->update([
            'status' => $request->status
        ]);

        return response()->json([
            "success"=> true,
            "message"=> 'Successfully saved'
        ]);
    }

}
