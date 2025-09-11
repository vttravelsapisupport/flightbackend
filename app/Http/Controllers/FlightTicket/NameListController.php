<?php

namespace App\Http\Controllers\FlightTicket;

use App\Exports\NameListExport;
use App\Http\Controllers\Controller;
use App\Mail\NameListEmail;
use App\Models\FlightTicket\Agent;
use App\Models\FlightTicket\Airline;
use App\Models\FlightTicket\BookTicket;
use App\Models\FlightTicket\Destination;
use App\Models\FlightTicket\NameListStatus;
use App\PurchaseEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class NameListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $destinations = Destination::where('status', 1)->get();
        $airlines = Airline::where('status', 1)->pluck('name', 'id')->all();

        $q = PurchaseEntry::join('airlines','airlines.id','=','purchase_entries.airline_id')
                ->join('destinations','destinations.id','=','purchase_entries.destination_id')
                ->join('owners','owners.id','=','purchase_entries.owner_id')
                ->whereDate('purchase_entries.travel_date','>=','2023-04-01')
                ->orderBy('id', 'DESC');

        if ($request->has('search'))
        {
            if ($request->has('destination_id') && $request->destination_id != '')
                $q->where('purchase_entries.destination_id', $request->destination_id);


            if ($request->has('pnr_no') && $request->pnr_no != '')
                $q->where('purchase_entries.pnr', $request->pnr_no);

            if ($request->has('name_list') && $request->name_list != '') {
                $q->whereDate('purchase_entries.name_list', Carbon::parse($request->name_list));
            }

            if ($request->has('travel_date') && $request->travel_date != '') {
                $q->whereDate('purchase_entries.travel_date', Carbon::parse($request->travel_date));
            }

            if ($request->has('airline') && $request->airline != '')
                $q->where('purchase_entries.airline_id', $request->airline);

            if ($request->has('exclude_zero') && $request->exclude_zero != '')
                $q->where(function ($query) {
                    $query->where('purchase_entries.available', '>', 100)
                        ->orWhere('purchase_entries.blocks', '>', 0);
                });
        } else{
            
            $q->whereDate('purchase_entries.name_list', Carbon::now());
        }


        $data = $q->select(
                'airlines.name as airline_name',
                'destinations.name as destination_name',
                'purchase_entries.pnr',
                'purchase_entries.quantity',
                'purchase_entries.namelist_status',
                'purchase_entries.id',
                'purchase_entries.blocks',
                'purchase_entries.available',
                'purchase_entries.travel_date',
                'purchase_entries.departure_time',
                'purchase_entries.arrival_time',
                'purchase_entries.name_list',
                'owners.is_third_party',
                'owners.name as owner_name')->simplePaginate(100);



        return view('flight-tickets.pnr-name-list.index', compact('data', 'airlines', 'destinations'));
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
        $psg_id_details = $request->session()->get('name_list_psg_ids');

        $data = [
            'purchase_entry_id' => $request->ticket_id,
            'type' => $request->type,
            'passenger_ids' => $psg_id_details,
            'remarks' => $request->remarks,
            'name' => $request->name,
            'owner_id' => Auth::id(),
        ];
        $nameListDetails = NameListStatus::create($data);

        $bookTicketDetail = PurchaseEntry::find($request->ticket_id)->update([
            'namelist_status' => $request->type
        ]);

        $request->session()->flash('success', 'Successfully Saved');

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $q1 = PurchaseEntry::where('id', '>', 0);

        if ($request->has('pnr_no')) {
            $q1->where('pnr', $request->pnr_no);
        } else {
            $q1->where('id', $id);
        }


        $data = $q1->first();


        if(!$data){
            $request->session()->flash('error', 'PNR Doesn\'t Exist');
            return back();
        }

        $q =  BookTicket::with('passenger_details');

        if ($request->has('pnr_no')) {
            $q->where('pnr', $request->pnr_no);
        } else {
            $q->where('purchase_entry_id', $id);
        }
        $bookSummaries = $q->get();
        $psg_details  = [];
        $psg_ids = [];
        foreach ($bookSummaries as $key => $val) {
            $agent = Agent::find($val->agent_id);
            foreach ($val->passenger_details->whereIn('is_refund', [0 , 2]) as $val1) {
                $val1->agentName = $agent->company_name;
                $val1->agentPhone = $agent->phone;
                array_push($psg_details, $val1);
                array_push($psg_ids, $val1->id);
            }
        }
        $nameliststatus  = NameListStatus::where('purchase_entry_id', $id)->get();
        $request->session()->put('name_list_psg_ids', $psg_ids);

        return view('flight-tickets.pnr-name-list.show', compact('psg_details', 'data', 'psg_ids', 'nameliststatus'));
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

    public function emailNameList($id, Request $request)
    {
        $email = $request->email;

        $ticketDetails = PurchaseEntry::find($id);
        $bookSummaries = BookTicket::with('passenger_details')
            ->where('purchase_entry_id', $id)
            ->get();

        $psg_details  = [];
        $infant_details = [];
        $psg_ids = [];

        foreach ($bookSummaries as $key => $val) {
            $agent = Agent::find($val->agent_id);
            foreach ($val->passenger_details->where('is_refund', 0) as $val1) {
                if($val1->type == 3){
                    $val1->agentName = $agent->company_name;
                    $val1->agentPhone = $agent->phone;
                    array_push($infant_details, $val1);
                    array_push($infant_details, $val1->id);
                }else{
                    $val1->agentName = $agent->company_name;
                    $val1->agentPhone = $agent->phone;
                    array_push($psg_details, $val1);
                    array_push($psg_ids, $val1->id);
                }

            }
        }

        Mail::to($email)->send(new NameListEmail($psg_details, $ticketDetails,$infant_details));
        $request->session()->flash('success', 'Successfully Send Email');

        return redirect(route('pnr-name-list.show', $id));
    }
    public function excel(Request $request)
    {
        $data = new NameListExport($request);
        if ($data->collection()->isEmpty()) {
            return back()->with('error', 'Cannot export an empty data sheet.');
        }
        return Excel::download($data, 'pnr-name-list-export.xlsx');
    }


    public function get_seat_live_count($id) {
        $seat_live_count = DB::table('book_tickets')
            ->join('book_ticket_details', 'book_tickets.id', '=', 'book_ticket_details.book_ticket_id')
            ->where('book_tickets.purchase_entry_id', $id)
            ->where('book_ticket_details.is_refund', 2)
            ->count();
        return $seat_live_count;
    }
}
