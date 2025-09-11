<?php

namespace App\Models\FlightTicket;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use DateTimeInterface;

class BookTicketOrn extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'book_tickets_orn';

    protected $fillable = [
        'child',
        'child_charge',
        'infant_charge',
        'bill_no',
        'agent_id',
        'destination_id',
        'purchase_entry_id',
        'destination', 'pnr', 'adults', 'infants', 'pax_price', 'travel_date', 'travel_time', 'airline', 'remark', 'created_by', 'display_price','booking_source',
        'status'
    ];

    //protected $dates = ['travel_date'];

    protected $casts = [
        'travel_date' => 'datetime',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d');
    }

    public function airline()
    {
        return $this->belongsTo('App\Models\FlightTicket\Airline');
    }
    public function airlineDetails()
    {
        return $this->belongsTo('App\Models\FlightTicket\Airline', 'airline', 'id');
    }
    public function destination()
    {
        return $this->belongsTo('App\Models\FlightTicket\Destination', 'destination_id', 'id');
    }

    public function destinationDetails()
    {
        return $this->belongsTo('App\Models\FlightTicket\Destination', 'destination_id', 'id');
    }
    public function owner()
    {
        return $this->belongsTo('App\Models\FlightTicket\Owner');
    }
    public function agent()
    {
        return $this->belongsTo('App\Models\FlightTicket\Agent');
    }
    public function passenger_details()
    {
        return $this->hasMany('App\Models\FlightTicket\BookTicketSummaryOrn');
    }
    public function passenger_details_names()
    {
        return $this->hasMany('App\Models\FlightTicket\BookTicketSummaryOrn','book_ticket_id','book_ticket_id');
    }
    public function get_passenger_details_adult()
    {
        return $this->hasMany('App\Models\FlightTicket\BookTicketSummaryOrn')->where('type', 1);
    }
    public function get_passenger_details_child()
    {
        return $this->hasMany('App\Models\FlightTicket\BookTicketSummaryOrn')->where('type', 2);
    }
    public function get_passenger_details_infants()
    {
        return $this->hasMany('App\Models\FlightTicket\BookTicketSummaryOrn')->where('type', 3);
    }
    public function purchase_entry()
    {
        return $this->belongsTo('App\PurchaseEntry', 'purchase_entry_id', 'id');
    }
    public function agentDetails()
    {
        return $this->belongsTo('App\Models\FlightTicket\Agent', 'agent_id', 'id');
    }

    public function owners()
    {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    public function intimations(){
        return $this->belongsTo('App\Models\FlightTicket\SaleTicketIntimation','id','book_ticket_id')->orderBy('id','DESC');
    }
}
