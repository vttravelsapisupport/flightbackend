<?php

namespace App\Models\FlightTicket\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Receipt extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    protected $fillable =[
        'agent_id',
        'receipt_no',
        'date',
        'owner_id',
        'account_transaction_id',
        'reference_no',
        'amount',
        'payment_mode',
        'status',
        'image',
        'bank_id',
        'remarks',
        'created_at'
    ];
    protected  $dates = [
        'date'
    ];
    public function agentDetails(){
        return $this->belongsTo('App\Models\FlightTicket\Agent','agent_id');
    }
    public function bankDetails(){
        return $this->belongsTo('App\Models\FlightTicket\Accounts\CompanyBankDetail','bank_id');
    }
    public function owner(){
        return $this->belongsTo('App\User','owner_id');
    }
}
