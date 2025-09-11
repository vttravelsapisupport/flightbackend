<?php

namespace App\Models\FlightTicket\Accounts;

use Illuminate\Database\Eloquent\Model;

class CompanyBankDetail extends Model
{
    protected  $fillable = ['name','account_no','status','bank_name'];
}
