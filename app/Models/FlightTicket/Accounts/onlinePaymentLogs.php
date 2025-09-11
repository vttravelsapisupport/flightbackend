<?php

namespace App\Models\FlightTicket\Accounts;

use Illuminate\Database\Eloquent\Model;

class onlinePaymentLogs extends Model
{
    protected $dates= ['transaction_date'];
    public function agent(){
        return $this->belongsTo('App\Models\FlightTicket\Agent');
    }
}
