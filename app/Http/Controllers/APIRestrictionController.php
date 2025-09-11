<?php

namespace App\Http\Controllers;

use App\Models\FlightTicket\Agent;
use App\Models\FlightTicket\Airline;
use App\Models\FlightTicket\AirlineSectorRestriction;
use App\Models\FlightTicket\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class APIRestrictionController extends Controller
{

    public function __construct(){
        $this->middleware('permission:airline-sector-restriction create', ['only' => ['store']]);
        $this->middleware('permission:airline-sector-restriction show', ['only' => ['list']]);
        $this->middleware('permission:airline-sector-restriction update', ['only' => ['updateStatus']]);

    }
    
    public function index(Request $request) {
        $agents = Agent::where('status', 1)->orderBy('id', 'ASC')->get();
        $airlines = Airline::where('status',1)->pluck('name','id')->all();
        $sectors = Destination::where('status',1)->pluck('name','id')->all();

        return view('settings.restriction.search-restriction')
            ->with('airlines',$airlines)
            ->with('sectors',$sectors)
            ->with('agents', $agents)
            ->with('request', $request);
    }



    public function list(Request $request) {
        $agents = Agent::where('status', 1)->orderBy('id', 'ASC')->get();
        $airlines = Airline::where('status',1)->pluck('name','id')->all();
        $sectors = Destination::where('status',1)->pluck('name','id')->all();

        $q = AirlineSectorRestriction::orderBy('id', 'DESC');

        if ($request->has('agent') && $request->agent) {
            $q->where('agent_id', $request->agent);
        }

        if ($request->has('type') && $request->type) {
            $q->where('type', $request->type);
        }

        if ($request->has('flight') && $request->flight) {
            $q->where('airline_id', $request->flight);
        }

        if ($request->has('sector') && $request->sector) {
            $q->where('destination_id', $request->sector);
        }

        if ($request->status != "") {
            $q->where('status', $request->status);
        }

        $airlineSectorRestriction = $q->get();

        return view('settings.restriction.search-restrictions')
            ->with('airlines',$airlines)
            ->with('sectors',$sectors)
            ->with('agents', $agents)
            ->with('airlineSectorRestriction' , $airlineSectorRestriction)->with('request', $request);
    }




    public function getOptions(Request $request) {

        $data = $request->all();
        $html = '';
        if($data['type'] == 1) { // sector
            $sectors = $this->getAgentSectors($data['agent_id']);
            foreach($sectors as $value) {
                $html.='<option value="'.$value->id.'">'.$value->name.'</option>';
            }
        }else{
            $airlines = $this->getAgentAirlines($data['agent_id']);
            foreach($airlines as $value) {
                $html.='<option value="'.$value->id.'">'.$value->name.'</option>';
            }
        }

        return response()->json(['success' => true, 'message' => $html]);
    }




    public function getAgentSectors($agent_id) {
        $sectors = DB::table('airline_sector_restrictions')->where('agent_id',$agent_id)->whereRaw('destination_id IS NOT NULL')->get();
        $tempArr = [];

        if(isset($sectors[0])) {
            foreach($sectors as $value) {
                array_push($tempArr, $value->destination_id);
            }
            $tempArr = join(",",$tempArr);
            $query = "SELECT * FROM destinations WHERE ID NOT IN ($tempArr)";
        }else{
            $query = "SELECT * FROM destinations";
        }

        $res = DB::select(DB::raw($query));

        return $res;
    }




    public function getAgentAirlines($agent_id) {

        $airlines = DB::table('airline_sector_restrictions')->where('agent_id',$agent_id)->whereRaw('airline_id IS NOT NULL')->get();
        $tempArr = [];

        if(isset($airlines[0])) {
            foreach($airlines as $value) {
                array_push($tempArr, $value->airline_id);
            }
            $tempArr = join(",",$tempArr);
            $query = "SELECT * FROM airlines WHERE ID NOT IN ($tempArr)";
        }else{
            $query = "SELECT * FROM airlines";
        }
        $res = DB::select(DB::raw($query));

        return $res;
    }




    public function store(Request $request) {
        $data = $request->all();

        $airlineSectorRestriction = new AirlineSectorRestriction;
        $airlineSectorRestriction->agent_id = $data['agent'];
        $airlineSectorRestriction->type = $data['type'];
        if($data['type'] == 1) {
            $airlineSectorRestriction->destination_id = $data['airline_sector'];
        }else{
            $airlineSectorRestriction->airline_id = $data['airline_sector'];
        }

        $airlineSectorRestriction->save();

        return redirect()->route('search-restrictions.index')->with('success', 'Added Restriction Successfully');
    }


    public function updateStatus(Request $request)
    {
        $id = $request->get('id');
        $type = $request->get('value');

        if($type == 'activate') {
            $status = 1;
        }else{
            $status = 0;
        }

        $airlineSectorRestriction = AirlineSectorRestriction::find($id);
        $airlineSectorRestriction->status = $status;
        $airlineSectorRestriction->save();

        return response()->json([
            'success' => true
        ]);
    }
}
