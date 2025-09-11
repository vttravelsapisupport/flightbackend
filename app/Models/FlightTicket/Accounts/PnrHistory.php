<?php

namespace App\Models\FlightTicket\Accounts;

use Illuminate\Database\Eloquent\Model;
use App\PurchaseEntry;

class PnrHistory extends Model
{
    protected $table = 'pnr_histories';

    protected $fillable = [
        'payment_date',
        'pnr',
        'passenger_name',
        'amount',
        'parent_pnr',
        'airline_code',
        'remarks'
    ];


}
