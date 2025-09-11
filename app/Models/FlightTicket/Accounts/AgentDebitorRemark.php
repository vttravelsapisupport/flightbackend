<?php

namespace App\Models\FlightTicket\Accounts;

use Illuminate\Database\Eloquent\Model;

class AgentDebitorRemark extends Model
{
    protected $fillable = [
        'agent_id', 'remarks', 'owner_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'owner_id', 'id');
    }
}
