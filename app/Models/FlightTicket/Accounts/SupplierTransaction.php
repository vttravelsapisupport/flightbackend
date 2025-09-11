<?php

namespace App\Models\FlightTicket\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierTransaction extends Model
{
    use SoftDeletes;
    protected $fillable = ['supplier_id', 'type', 'ticket_id', 'amount', 'remarks', 'owner_id', 'reference_no', 'exp_date', 'balance', 'created_at'];
    protected  $dates = ['exp_date'];

    public function suplier()
    {
        return $this->belongsTo('App\Models\FlightTicket\owner');
    }

    public function ticket()
    {
        return $this->belongsTo('App\Models\FlightTicket\BookTicket', 'ticket_id', 'id');
    }

    public function owner()
    {
        return $this->belongsTo('App\User');
    }
}
