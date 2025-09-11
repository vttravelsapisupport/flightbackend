<?php

namespace App\Http\Controllers\FlightTicket;

use App\Http\Controllers\Controller;
use App\Mail\CancelRequestRemark;
use App\Models\CancellationRequest;
use App\Models\FlightTicket\Agent;
use App\Models\FlightTicket\Airline;
use App\Models\FlightTicket\BookTicket;
use App\Models\FlightTicket\BookTicketSummary;
use App\Models\FlightTicket\Destination;
use App\PurchaseEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CancellationRequestController extends Controller
{

    public function __construct(){
        $this->middleware('permission:flight-cancellation show', ['only' => ['index']]);
        $this->middleware('permission:flight-cancellation liveseat|flight-cancellation reject|flight-cancellation approve', ['only' => ['update']]);
    }

    public function index(Request $request)
    {
        $destinations = Destination::where('status', 1)->get();
        $airlines     =  Airline::pluck('name','id')->all();
        $agents = Agent::where('status', 1)->get();

        $q = CancellationRequest::orderBy('id', 'DESC');
        $q->join('book_tickets', 'cancellation_requests.book_id', '=', 'book_tickets.id');
        $q->join('purchase_entries', 'book_tickets.purchase_entry_id', '=', 'purchase_entries.id');
        $q->join('owners', 'purchase_entries.owner_id', '=', 'owners.id');
        $q->join('agents', 'cancellation_requests.agent_id', '=', 'agents.id');
        $q->whereDate('purchase_entries.travel_date','>=','2023-04-01');
        $q->select('cancellation_requests.id', 'cancellation_requests.book_id', 'cancellation_requests.created_at', 'cancellation_requests.status','cancellation_requests.owner_id', 'book_tickets.bill_no', 'book_tickets.destination', 'book_tickets.pnr', 'book_tickets.pax_price', 'book_tickets.travel_date', 'book_tickets.airline', 'book_tickets.created_at as booked_at','agents.company_name', 'owners.name as owner_name');

        if ($request->has('agent_id') && $request->agent_id) {
            $q->where('cancellation_requests.agent_id', $request->agent_id);
        }

        if ($request->has('destination_id') && $request->destination_id) {
            $q->where('book_tickets.destination_id', $request->destination_id);
        }

        if ($request->has('bill_no') && $request->bill_no) {
            $q->where('book_tickets.bill_no', $request->bill_no);
        }

        if ($request->has('pnr_no') && $request->pnr_no) {
            $q->where('book_tickets.pnr', $request->pnr_no);
        }

        if ($request->has('status') && $request->status) {
            $q->where('cancellation_requests.status', $request->status);
        }

        $datas = $q->simplePaginate(50);

        return view('flight-tickets.cancellations.index', compact('destinations', 'airlines', 'datas', 'agents'));
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
    public function show(Request $request)
    {
        $id = $request->get('id');
        $cancelRequest = CancellationRequest::find($id);
        $bookTicketDetails = BookTicketSummary::whereIn('id', json_decode($cancelRequest->passenger_ids))->get();
        $html = view('modal.pax-details')->with('book_ticket_details', $bookTicketDetails)->render();
        return $html;
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

        $cancelRequest = CancellationRequest::find($id);
        if($cancelRequest->status == 2 || $cancelRequest->status == 3) {
            return response()->json(['success' => false, 'message' => 'Cannot reject this request']);
        }
        $book_id = $cancelRequest->book_id;

        $bookingTicketDetail  = BookTicket::find($book_id);
        if(!$bookingTicketDetail) {
            return response()->json(['success' => false, 'message' => 'Not able to find booking']);
        }
        $purchaseEntryDetail  = PurchaseEntry::where('id', $bookingTicketDetail->purchase_entry_id)->first();
        $status = $request->get('status');
        $user = Auth::user();

        DB::beginTransaction();
        try{
            if ($status  == 3) { //check with rejected status
                if($cancelRequest->status == 4) { //check if seat live status
                    $total_pax = $cancelRequest->passenger_ids;
                    $total_pax = json_decode($total_pax);
                    $purchaseEntryDetail->decrement('available',  count($total_pax));
                    $purchaseEntryDetail->increment('sold', count($total_pax));
                    BookTicketSummary::whereIn('id', $total_pax)->update(['is_refund' => 0]); // For seat live
                }
                $cancelRequest->status = $status;
                $cancelRequest->owner_id = $user->id;
                $cancelRequest->save();

                DB::commit();

                $html = view('modal.cancel-rejection-remarks')->with('cancel_request_id', $id)->render();
                return response()->json(['success' => true, 'message' => $html]);
            }
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

        $request->session()->flash('success', 'Successfully Updated Cancellation Request');
        return response()->json(['success' => true, 'message' => 'Successfully Updated Cancellation Request']);
    }




    public function updateRemarks(Request $request) {

        $cancelRequest = CancellationRequest::find($request->get('cancel_request_id'));
        $agent = Agent::find($cancelRequest->agent_id);
        $cancelRequest->admin_remarks = $request->get('message');
        $cancelRequest->save();
        $bookingTicketDetail  = BookTicket::find($cancelRequest->book_id);
        $booking_passengers = BookTicketSummary::where('book_ticket_id', $cancelRequest->book_id)->get();
        $message = $request->get('message');

        if($cancelRequest->status == 3) {
            $subject = "CANCELLATION REQUEST DETAILS OF " . $bookingTicketDetail->bill_no;
        }else{
            $subject = "CANCELLATION REQUEST DETAILS OF " . $bookingTicketDetail->bill_no;
        }

        $heading = '';
        if($cancelRequest->status == 3) {
            $heading = "Your cancellation for reference " . $bookingTicketDetail->bill_no . "has been rejected successfully. Below is admin remarks ";
        }else{
            $heading = "Seat is live against your cancellation reference " . $bookingTicketDetail->bill_no . ". Below is admin remarks";
        }

        try{
            Mail::to($agent->email)->cc('support@vishaltravels.in')->send(new CancelRequestRemark($cancelRequest, $subject, $message, $agent, $bookingTicketDetail, $booking_passengers,  $heading));
        }catch(\Exception $e){
            Log::error("MAIL ERROR - ".  $e->getMessage());
        }

        $request->session()->flash('success', 'Successfully Updated Cancellation Request');
        return response()->json(['success' => true, 'message' => 'Successfully Updated Cancellation Request']);
    }



    public function cancelRequestApproved(Request $request) {
        $cancelRequest = CancellationRequest::find($request->get('id'));
        $cancelRequest->status = 2;
        $cancelRequest->save();

        return response()->json(['success' => true, 'message' => 'Successfully Updated Cancellation Request']);
    }
}
