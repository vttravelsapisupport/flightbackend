<?php

namespace App\Http\Controllers;

use App\Models\BookingRequestLog;
use Illuminate\Http\Request;

class BookingRequestLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $booking_request_logs = BookingRequestLog::join('agents','agents.id','=','booking_request_logs.agent_id')
                                                 ->join('users','users.id','=','booking_request_logs.user_id')
                                                ->select('agents.company_name','agents.code','users.first_name','users.last_name','booking_request_logs.*')
                                                ->orderby('booking_request_logs.id','DESC')
                                                ->paginate(50);

        return view('flight-tickets.booking-request-logs.index',compact('booking_request_logs'));
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
     * @param  \App\Models\BookingRequestLog  $bookingRequestLog
     * @return \Illuminate\Http\Response
     */
    public function show(BookingRequestLog $bookingRequestLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BookingRequestLog  $bookingRequestLog
     * @return \Illuminate\Http\Response
     */
    public function edit(BookingRequestLog $bookingRequestLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BookingRequestLog  $bookingRequestLog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BookingRequestLog $bookingRequestLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BookingRequestLog  $bookingRequestLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookingRequestLog $bookingRequestLog)
    {
        //
    }
}
