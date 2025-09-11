<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentMarkup extends Model
{
    use HasFactory;
    protected $fillable = [
        'agent_id',
        'markup_price',
        'status'
    ];

    public function agent(){
        return $this->belongsTo('App\Models\FlightTicket\Agent');
    }
}
