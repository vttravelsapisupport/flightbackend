<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingRequestLog extends Model
{
    use HasFactory;

    protected $dates =['departure_date','arrival_date'];
    
}
