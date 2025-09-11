<?php

namespace App\Models\FlightTicket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseTicketFareLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'new_price',
        'purchase_entry_id',
        'owner_id'
    ];

    public function owner()
    {
        return $this->belongsTo('App\User','owner_id','id');
    }
}
