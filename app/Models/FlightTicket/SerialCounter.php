<?php

namespace App\Models\FlightTicket;

use Illuminate\Database\Eloquent\Model;

class SerialCounter extends Model
{
    protected $fillable = ['name','count'];
}
