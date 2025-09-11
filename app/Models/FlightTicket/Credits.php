<?php

namespace App\Models\FlightTicket;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Credits extends Model
{
    use SoftDeletes;
    protected  $table = 'account_transaction';
    protected $fillable = ['agent_id', 'type', 'ticket_id', 'amount', 'remarks', 'owner_id', 'reference_no', 'exp_date', 'balance', 'created_at'];
    protected  $dates = ['exp_date'];

    public function agent()
    {
        return $this->belongsTo('App\Models\FlightTicket\Agent');
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
