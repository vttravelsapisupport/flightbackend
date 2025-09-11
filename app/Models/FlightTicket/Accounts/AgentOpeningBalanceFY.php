<?php

namespace App\Models\FlightTicket\Accounts;

use Illuminate\Database\Eloquent\Model;

class AgentOpeningBalanceFY extends Model
{
    protected $fillable = [
        'fys_id',
        'agent_id',
        'amount',
        'isActive'

    ];
}
