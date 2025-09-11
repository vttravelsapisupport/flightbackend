<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\SaleTicketNotification;
use App\Models\FlightTicket\Accounts\SupplierTransaction;
use App\Models\FlightTicket\Agent;
use App\Models\FlightTicket\Airline;
use App\Models\FlightTicket\BookTicketOrn;
use App\Models\FlightTicket\BookTicketSummary;
use App\Models\FlightTicket\Credits;
use App\Models\FlightTicket\Destination;
use App\Models\FlightTicket\Owner;
use App\Models\FlightTicket\SaleTicketIntimation;
use App\Models\FlightTicket\TicketAdditionalServiceList;
use App\Models\FlightTicket\TicketService;
use App\PurchaseEntry;
use App\Services\AgentService;
use App\Services\CreditService;
use App\Services\SupplierService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class OrnBookingController extends Controller
{
    
    public function index(Request $request)
    {

        // $destinations = Destination::where('status',1)->pluck('name','id')->all();

        $agent = [];
        $destinations = Destination::where('status', 1)->get();
        $owners       = Owner::orderby('name','ASC')->where('is_third_party',0)->pluck('name', 'id')->all();

        $suppliers    = Owner::orderby('name','ASC')->where('is_third_party', '=', 1)->pluck('name', 'id')->all();

        $airlines = Airline::where('status', 1)->pluck('name', 'id')->all();

        $q= BookTicketOrn::join('agents','agents.id','=','book_tickets_orn.agent_id')
            ->join('destinations as d', 'd.id', '=', 'book_tickets_orn.destination_id')
            ->join('purchase_entries as p','p.id','=','book_tickets_orn.purchase_entry_id')
            ->join('users','users.id','=','book_tickets_orn.created_by')
            ->join('owners','owners.id','=','p.owner_id')
            ->whereDate('book_tickets_orn.created_at','>=', '2023-04-01')
            ->orderBy('book_tickets_orn.id', 'DESC');


        if ($request->has('owner_id') && $request->owner_id != '')
            $q->where('p.owner_id', $request->owner_id);

        if ($request->has('supplier_id') && $request->supplier_id != '')
            $q->where('p.owner_id', $request->supplier_id);


        if ($request->has('destination_id') && $request->destination_id != '')
            $q->where('book_tickets_orn.destination_id', $request->destination_id);

        if ($request->has('pnr_no') && $request->pnr_no != '') {
            $q->where('book_tickets_orn.pnr', 'like', '%' . $request->pnr_no . '%');
        }
        if ($request->has('travel_date') && $request->travel_date != '')
            $q->whereDate('book_tickets_orn.travel_date', Carbon::parse($request->travel_date));



            if ($request->has('airline') && $request->airline != '')
            $q->where('p.airline_id', $request->airline);

        if ($request->has('agent_id') && $request->agent_id != ''){
            $agent = Agent::find($request->agent_id);
            $q->where('book_tickets_orn.agent_id', $request->agent_id);
        }


        if ($request->has('bill_no') && $request->bill_no != '')
            $q->where('book_tickets_orn.bill_no', $request->bill_no);



        if ($request->get('source'))
        {
            if($request->source == 'admin') {
                $q->whereNull('book_tickets_orn.booking_source');
            }

            if($request->source == 'portal') {
                $q->where('book_tickets_orn.booking_source', 'portal');
            }

            if($request->source == 'api') {
                $q->where('book_tickets_orn.booking_source', 'api');
            }
        }


        $data = $q->select('book_tickets_orn.*','agents.company_name','d.name as destination_name','p.flight_no','p.namelist_status','owners.name as owner_name','owners.is_third_party as owner_type' ,'p.deleted_at as p_deleted_at'
            ,DB::raw('CONCAT(users.first_name,users.last_name) as user_name'), DB::raw('(SELECT COUNT(*) FROM book_ticket_details_orn WHERE book_ticket_id=book_tickets_orn.id AND is_refund=2) as seat_live_count'))
            ->simplePaginate(50);


        return view('orn-booking.index', compact('data', 'destinations', 'airlines', 'owners','suppliers','agent'));
    }

    
}
