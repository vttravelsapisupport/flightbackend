<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentPayment extends Model
{
    use HasFactory;
    protected $table = 'agent_payments';
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
