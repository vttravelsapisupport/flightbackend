<?php

namespace App\Models\FlightTicket\Accounts;

use Illuminate\Database\Eloquent\Model;

class SupplierPayment extends Model
{
    protected $fillable = [
        'supplier_id',
        'supplier_bank_detail_id',
        'amount',
        'payment_mode',
        'transaction_id',
        'attachments',
        'remarks',
        'created_by',
        'ip',
        'user_agent',
        'created_at'
    ];

    public function supplier_bank(){
        return $this->belongsTo('App\Models\FlightTicket\Accounts\SupplierBankDetails','supplier_bank_detail_id','id');
    }

    public function created_details(){
        return $this->belongsTo('App\User','created_by','id');
    }
}
