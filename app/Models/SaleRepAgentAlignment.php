<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleRepAgentAlignment extends Model
{
    protected $fillable = ['sales_rep_id','agent_id'];

    public function agent()
    {
        return $this->belongsTo('App\Models\FlightTicket\Agent');
    }
}
