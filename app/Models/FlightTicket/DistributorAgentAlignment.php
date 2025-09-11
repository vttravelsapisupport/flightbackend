<?php

namespace App\Models\FlightTicket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributorAgentAlignment extends Model
{
    protected  $fillable = [
        'distributor_id',
        'agent_id',
        'status',
   ];

   public function distributor(){
       return $this->belongsTo('App\Models\FlightTicket\Agent');
   }


   public function agent(){
       return $this->belongsTo('App\Models\FlightTicket\Agent');
   }
}
