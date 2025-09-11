<?php

namespace App\Models\FlightTicket;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketService extends Model
{
    use SoftDeletes;
    protected $fillable = ['internal_remarks', 'external_remarks', 'amount', 'book_tickets_id', 'additional_service_id', 'isrefund', 'status', 'created_at'];


    public function additional_service()
    {
        return $this->belongsTo('App\Models\FlightTicket\TicketAdditionalServiceList');
    }


    public function bookTicket()
    {
        return $this->belongsTo('App\Models\FlightTicket\BookTicket', 'book_tickets_id', 'id');
    }
}
