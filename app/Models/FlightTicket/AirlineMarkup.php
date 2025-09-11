<?php

namespace App\Models\FlightTicket;

use Illuminate\Database\Eloquent\Model;

class AirlineMarkup extends Model
{
    protected  $fillable = [
        'agent_id',
        'airline_id',
        'amount',
        'status'
    ];

    public function agent(){
        return $this->belongsTo('App\Models\FlightTicket\Agent');
    }


    public function airline(){
        return $this->belongsTo('App\Models\FlightTicket\Airline');
    }
}
