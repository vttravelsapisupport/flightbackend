<?php

namespace App\Models\FlightTicket;

use Illuminate\Database\Eloquent\Model;

class ApiOwnersDetail extends Model
{

    protected  $fillable = [
        'supplier_id',
        'owner_balance',
        'credentials',
        'markup'
    ];

}
