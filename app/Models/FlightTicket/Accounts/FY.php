<?php

namespace App\Models\FlightTicket\Accounts;

use Illuminate\Database\Eloquent\Model;

class FY extends Model
{
    protected $dates= ['financial_year_start','financial_year_end'];
    protected $fillable = ['name','financial_year_start','financial_year_end','isActive'];
}
