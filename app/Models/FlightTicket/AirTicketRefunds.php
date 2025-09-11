<?php

namespace App\Models\FlightTicket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;


class AirTicketRefunds extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected  $fillable = [
        'agent_id',
        'book_ticket_id',
        'passenger_ids',
        'pax_cost',
        'total_refund',
        'pax',
        'owner_id',
        'remarks',
        'status',
        'adult',
        'child',
        'infant',
        'account_transaction_id',
        'supplier_refund_pax_price',
        'supplier_total_refund',
        'supplier_balance',
        'supplier_transaction_id',
        'wallet_type'
    ];

    protected $casts = ['passenger_ids' => 'array'];

    public function agent()
    {
        return $this->belongsTo('App\Models\FlightTicket\Agent');
    }
    public function bookTicket()
    {
        return $this->belongsTo('App\Models\FlightTicket\BookTicket', 'book_ticket_id');
    }

    public function owner()
    {
        return $this->belongsTo('App\User');
    }
}
