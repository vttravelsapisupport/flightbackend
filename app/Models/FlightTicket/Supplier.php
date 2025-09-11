<?php

namespace App\Models\FlightTicket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected  $fillable = [
        'id',
        'name',
        'mobile',
        'email',
        'city',
        'password',
        'description',
        'is_third_party',
        'status'
    ];
}
