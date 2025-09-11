<?php

namespace App\Models\FlightTicket\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierBankDetails extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'account_holder_name',
        'bank_name',
        'branch',
        'bank_account_no',
        'ifsc_code',
        'attachment',
        'status',
        'isVerified',
        'created_at',
        'updated_at'

    ];
}
