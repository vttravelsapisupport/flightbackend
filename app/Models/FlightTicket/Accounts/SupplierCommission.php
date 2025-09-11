<?php

namespace App\Models\FlightTicket\Accounts;

use Illuminate\Database\Eloquent\Model;

class SupplierCommission extends Model
{
    protected $fillable = [
        'supplier_id',
        'supplier_bank_detail_id',
        'amount',
        'transaction_id',
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
