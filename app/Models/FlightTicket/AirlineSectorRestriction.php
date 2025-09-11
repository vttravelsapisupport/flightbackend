<?php

namespace App\Models\FlightTicket;

use Illuminate\Database\Eloquent\Model;

class AirlineSectorRestriction extends Model
{
    public function agent(){
        return $this->belongsTo('App\Models\FlightTicket\Agent');
    }

    public function airline(){
        return $this->belongsTo('App\Models\FlightTicket\Airline');
    }

    public function destination(){
        return $this->belongsTo('App\Models\FlightTicket\Destination');
    }
}
