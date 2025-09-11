<?php

namespace App\Http\Controllers;

use App\Models\FlightTicket\Agent;
use App\Models\FlightTicket\Airport;
use Carbon\Carbon;
use App\Models\FlightTicket\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WhatsAppController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:mailer-notification show', ['only' => ['index']]);
    }

    public function sendMessage() {

        $queue = DB::table('whatsapp_queue')->first();

        if ($queue) {

            $results = DB::table('whatsapp_queue')
                ->join('whatsapp_notification_summery_agents', 'whatsapp_queue.summery_agent_id', '=', 'whatsapp_notification_summery_agents.id')
                ->join('whatsapp_notification_summery', 'whatsapp_queue.summery_id', '=', 'whatsapp_notification_summery.id')
                ->join('agents', 'whatsapp_notification_summery_agents.agent_id', '=', 'agents.id')
                ->where('whatsapp_queue.id', $queue->id)
                ->select('whatsapp_notification_summery.body_values as body_values', 'whatsapp_notification_summery.template_name as template_name', 'agents.whatsapp as whatsapp_no')
                ->first();
            
            $whatsapp_no = str_replace('+91', '', $results->whatsapp_no);
            $whatsapp_no = str_replace(' ', '', $whatsapp_no);
            
            // temporary code start
            echo json_encode(array('processed'=>true, 'whatsapp'=>$whatsapp_no, 'template'=>$results->template_name));
  
            DB::table('whatsapp_notification_summery_agents')
                    ->where('id', $queue->summery_agent_id)
                    ->update(['is_sent' => 1, 'intrakt_id' => 'dummy.id.8ad5712c-cddb-489b-ad6a-8569bb24b266']);
            DB::table('whatsapp_queue')->where('id', $queue->id)->delete();
            die;
            // temporary code end
            
            
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.interakt.ai/v1/public/message/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "countryCode": "+91",
                "phoneNumber": ' . $whatsapp_no . ',
                "callbackData": "some text here",
                "type": "Template",
                "template": {
                    "name": "' . $results->template_name . '",
                    "languageCode": "en",
                    "headerValues": [
                        "https://admin-v2.goflysmart.com/images/gfs-logo-full-compact.jpg"
                    ],
                    "bodyValues": ' . $results->body_values .'
                }
            }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic WS04andZSTNRNWZzZmxuWDc3ZlQ2emdkbTRhZGpINTRCWXNoSl9YdXFySTo=',
    //          'Authorization: Basic ' . config('constants.WHATSAPP_API_KEY'),
                'Content-Type: application/json',
                'Cookie: ApplicationGatewayAffinity=a8f6ae06c0b3046487ae2c0ab287e175; ApplicationGatewayAffinityCORS=a8f6ae06c0b3046487ae2c0ab287e175'
            ),
            ));
    
            $response = curl_exec($curl);
    
            curl_close($curl);
    
            $response_data = json_decode($response, true);
    
            if ($response_data['result'] == true) {

                DB::table('whatsapp_notification_summery_agents')
                    ->where('id', $queue->summery_agent_id)
                    ->update(['is_sent' => 1, 'intrakt_id' => $response_data['id']]);

                DB::table('whatsapp_queue')->where('id', $queue->id)->delete();

            } else {
                $error = str_replace("Please correct the following error - ", "", $response_data['message']);
                $error = str_replace("Please re-sync on Interakt dashboard and try again", "", $error);
                $error = str_replace("..", ".", $error);

            }
        }
    }

    public function send(Request $request) {

        $length = $request->input('whatsapp_data_count');

        if ($length >= 1 && $length <= 7) {
            $whatsapp_data = $request->input('whatsapp_data');

            ini_set('memory_limit', '256M');
            $agents = Agent::with(['tickets' => function ($query) {
                    }])
                        ->select('id', 'whatsapp')
                        ->where('status', 1)
                        ->whereIn('nearest_airport', $request->airport_id)
                        ->get();
            
            $summery_id = DB::table('whatsapp_notification_summery')->insertGetId([
                'ex' => $request->input('whatsapp_ex'),
                'days' => $request->input('whatsapp_days'),
                'sector_ids' => $request->input('whatsapp_destination_ids'),
                'airport_ids' => json_encode($request->airport_id),
                'template_name' => 'api_notificaion_' . $length,
                'body_values' => $whatsapp_data
            ]);

            foreach ($agents as $agent) {
                if ($agent->tickets) {
                    if ($agent->whatsapp) {

                        $summery_agent_id = DB::table('whatsapp_notification_summery_agents')->insertGetId([
                            'summery_id' => $summery_id,
                            'agent_id' => $agent->id
                        ]);

                        DB::table('whatsapp_queue')->insert([
                            'summery_id' => $summery_id,
                            'summery_agent_id' => $summery_agent_id,
                        ]);
                        
                    }
                }
            }
            return back()->with('success', 'Messages was queued for WhatsApp.');
        } else {
            return back()->with('error', 'Cannot send less than 1 and more than 7 sectors.');
        }
    }

    public function index(Request $request)
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

        if ($output_datas) {
            $whatsapp_data = [];
            foreach($output_datas as $i => $d) {
                $whatsapp_data[] = $i;
                foreach($d as $x => $y) {
                    $date = Carbon::parse($y->travel_date)->format('d M');
                    $whatsapp_data[] = $date . ' - ₹' . number_format($y->sell_price, 0, '.', ',') . '/-';
                }
            }
            return view('notifications.whatsapp.index', compact('ex', 'output_datas', 'destinations', 'whatsapp_data'));
        }

        return view('notifications.whatsapp.index', compact('ex', 'output_datas', 'destinations'));
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

  
}
