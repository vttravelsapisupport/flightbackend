<?php

namespace App\Models\FlightTicket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airline extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'is_domestic', 'status', 'code', 'helpline_no', 'infant_charge'];
}
