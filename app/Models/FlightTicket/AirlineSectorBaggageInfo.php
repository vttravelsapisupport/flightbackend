<?php

namespace App\Models\FlightTicket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirlineSectorBaggageInfo extends Model
{
    use HasFactory;
    protected $fillable = ['airline_code', 'sector_code', 'type', 'adult','child', 'infant', 'mode', 'user_id'];

}
