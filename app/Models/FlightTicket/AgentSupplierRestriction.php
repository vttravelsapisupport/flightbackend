<?php

namespace App\Models\FlightTicket;

use Illuminate\Database\Eloquent\Model;

class AgentSupplierRestriction extends Model
{

    protected $fillable = [
        'agent_id',
        'supplier_id',
        'user_id',
        'status',
    ];

    public function agent(){
        return $this->belongsTo('App\Models\FlightTicket\Agent');
    }

    public function supplier(){
        return $this->belongsTo('App\Models\FlightTicket\Owner','supplier_id','id');
    }
    public function user(){
        return $this->belongsTo('App\User');
    }

}
