<?php

namespace App\Models\FlightTicket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirlineBaggageInfo extends Model
{
    use HasFactory;
    protected $fillable = ['airline_code', 'type', 'adult','child', 'infant', 'mode', 'user_id'];

}
