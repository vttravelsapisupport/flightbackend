<?php

namespace App\Models\FlightTicket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirlineCancellationPolicy extends Model
{
    use HasFactory;
    protected $fillable = ['airline_id', 'slot_id', 'amount','int_amount'];

    public function cancellationSlots(){
        return $this->belongsTo('App\Models\FlightTicket\CancellationSlot','slot_id','id');
    }
}
