<?php

namespace App\Models\FlightTicket;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LivePNRStatus extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'flight_ticket_live_p_n_r_statuses';

    protected $dates =['travel_date'];
    protected $fillable= [
        'total_pax_count',
        'purchase_id',
        'pnr',
        'flight_no',
        'travel_date',
        'source',
        'destination',
        'dep_time',
        'arrival_time',
        'current_flight_status',
        'pnr_status',
        'passengers',
        'status'
    ];

    protected $casts = [
        'passengers' => 'array',
    ];
}
