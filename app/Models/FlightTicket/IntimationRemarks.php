<?php

namespace App\Models\FlightTicket;

use Illuminate\Database\Eloquent\Model;

class IntimationRemarks extends Model
{
    protected $fillable = [
       'initimation_id',
       'type',
        'remark',
       'user_id',
    ];
}
