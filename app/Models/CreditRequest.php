<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditRequest extends Model
{

    public function agent(){
        return $this->belongsTo('App\Models\FlightTicket\Agent');
    }


    public function owner()
    {
        return $this->belongsTo('App\User');
    }
}
