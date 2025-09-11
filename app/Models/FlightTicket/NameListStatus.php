<?php

namespace App\Models\FlightTicket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NameListStatus extends Model
{
    use HasFactory;
    protected  $fillable = [
        'purchase_entry_id' ,
        'type' ,
        'passenger_ids' ,
        'remarks' ,
        'name' ,
        'owner_id' ,
    ];
    protected  $casts = [
        'passenger_ids' => 'array'
    ];
}
