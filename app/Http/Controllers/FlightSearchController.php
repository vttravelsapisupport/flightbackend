<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\UtilService;
use App\Mail\BookingConfirmation;
use App\Services\GFSV2APIService;
use App\Services\WhatsappService;
use App\Models\FlightTicket\Agent;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\View\Factory;
use App\Models\FlightTicket\BookTicket;
use App\Models\FlightTicket\Destination;
use App\Models\FlightTicket\BookTicketSummary;
use Illuminate\Contracts\Foundation\Application;

class FlightSearchController extends Controller
{
    //
    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function searchFlightTicket(Request $request)
    {
        $this->validate($request,[
            'origin' => 'required|string|exists:airports,code',
            'destination' => 'required|string|exists:airports,code',
            'departure_date' => 'required|date_format:d-m-Y',
            'adult' => 'required|digits_between:0,9',
            'child' => 'required|digits_between:0,9',
            'infant' => 'required|integer|digits_between:0,9'
        ]);
        $airports = UtilService::getAirports();

        if($request->has('origin') && $request->origin != ''){
            $origin_details = $airports[$request->origin];
        }

        if($request->has('destination') && $request->destination != ''){
            $destination_details = $airports[$request->destination];
        }



        $origin_code = $request->origin;
        $destination_code = $request->destination;
        $departure_date = Carbon::parse($request->departure_date);
        $adult = $request->adult;
        $child = $request->child;
        $infant = $request->infant;
        $results = (new GFSV2APIService())
                ->getSearchResult($origin_code,$destination_code,$adult,$child,$infant,$departure_date);


        // return $destination_details;
        // return $origin_details;
        return view('search.index',compact('results','destination_details','origin_details'));
    }


    public function sendWhatsappTicket(Request $request,$ticket_id){

        $booking_data = BookTicket::find($ticket_id);

        $agent = Agent::where('id',$booking_data->agent_id)->first();

        $destinationDetail = Destination::where(
            'name',
            $booking_data->destination
        )->first();

        $book_ticket_details = BookTicketSummary::where(
            'book_ticket_id',
            $ticket_id
        )->get();

        $pdf = UtilService::createPDF($booking_data,$book_ticket_details,$destinationDetail);

        WhatsappService::sendFlightTicket($agent->phone,$agent->company_name,$booking_data->bill_no,$booking_data->pnr,$pdf["url"],$pdf['filename']);
        $request->session()->flash('success','Successfully Send Whatsapp to '.$agent->phone);
        return redirect()->back();
    }

    public function sendEmail(Request $request,$ticket_id){
        $booking_data = BookTicket::find($ticket_id);

        $agent = Agent::where('id',$booking_data->agent_id)->first();

        $destinationDetail = Destination::where(
            'name',
            $booking_data->destination
        )->first();

        $book_ticket_details = BookTicketSummary::where(
            'book_ticket_id',
            $ticket_id
        )->get();

        $pdf = UtilService::createPDF($booking_data,$book_ticket_details,$destinationDetail);

        Mail::to($agent->email)
                ->cc(['no-reply@mail.vishaltravels.in'])
                ->send(new BookingConfirmation($booking_data, $book_ticket_details, $pdf["url"]));

        $request->session()->flash('success','Successfully Send Email to '.$agent->phone);
        return redirect()->back();
    }
}
