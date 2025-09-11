<?php

namespace App\Models\FlightTicket;

use Illuminate\Database\Eloquent\Model;

class SpiceJetPNRReconciliationSummaryLog extends Model
{
    protected  $fillable = [
        'purchase_entry_id',
        'total_pax_count',
        'flight_no',
        'travel_date',
        'source',
        'destination',
        'dep_time',
        'arrival_time',
        'current_flight_status',
        'pnr_status',
    ];
}
