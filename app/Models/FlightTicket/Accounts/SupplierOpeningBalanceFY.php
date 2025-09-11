<?php

namespace App\Models\FlightTicket\Accounts;

use Illuminate\Database\Eloquent\Model;

class SupplierOpeningBalanceFY extends Model
{
    protected $fillable = [
        'fys_id',
        'supplier_id',
        'amount',
        'isActive',
        'created_at',
        'updated_at'
    ];

    public function Fy(){
        return $this->belongsTo('App\Models\FlightTicket\Accounts\FY','fys_id','id');
    }
}
