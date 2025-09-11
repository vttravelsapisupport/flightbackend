<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class DepositRequest extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'agent_id',
        'user_id',
        'files',
        'type',
        'amount',
        'ref_number',
        'account',
        'date',
        'phone',
        'bank',
        'remarks',
        'status'
];
    protected $dates = ['date'];

    public function agent(){
        return $this->belongsTo('App\Models\FlightTicket\Agent');
    }
}
