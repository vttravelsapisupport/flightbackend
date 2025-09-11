<?php

namespace App\Models\FlightTicket;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlockTicket extends Model
{
    use SoftDeletes;
    protected $fillable = ['agent_id', 'purchase_entry_id', 'quantity','created_by','remarks','created_by'];

    public function owner(){
        return $this->belongsTo('App\User','created_by','id');
    }
    public function agent(){
        return $this->belongsTo('App\Models\FlightTicket\Agent');
    }
    public function purchase_entry(){
        return $this->belongsTo('App\PurchaseEntry');
    }

}
