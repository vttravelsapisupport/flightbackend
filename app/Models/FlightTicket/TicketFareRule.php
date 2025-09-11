<?php

namespace App\Models\FlightTicket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketFareRule extends Model
{
    use HasFactory;
    protected $fillable = ['purchase_entry_id', 'slot_id', 'slot_desc', 'duration', 'user_id','amount'];

    
}
