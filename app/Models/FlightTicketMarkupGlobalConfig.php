<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightTicketMarkupGlobalConfig extends Model
{
    use HasFactory;
    protected $fillable = ['markup_price'];
}
