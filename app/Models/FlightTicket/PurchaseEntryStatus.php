<?php

namespace App\Models\FlightTicket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseEntryStatus extends Model
{
    //
    protected $fillable = [
        'user_id',
        'purchase_entry_id',
        'type',
        'remarks',
        'data',
    ];

    protected $date =['travel_date','arrival_date'];


    protected $casts = [
        'data' => 'array',
    ];


    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    }
}
