<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CancellationRequest extends Model
{
    use HasFactory;
    protected $fillable = [
            'book_id',
            'passenger_ids',
            'agent_id',
            'user_id',
            'status',
            'agent_remarks'
    ];

    public function agent(){
        return $this->belongsTo('App\Models\FlightTicket\Agent');
    }

    public function owner()
    {
        return $this->belongsTo('App\User', 'owner_id');
    }
     protected $casts = [
        'passenger_ids' => 'array',
    ];

}
