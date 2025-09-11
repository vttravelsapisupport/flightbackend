<?php

namespace App\Models\FlightTicket;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class BookTicket extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'src',
        'dest',
        'departureDate',
        'arrivalDate',
        'total_amount',
        'request',
        'externalPriceDetails',
        'externalReferenceId',
        'paxDetails',
        'base_price','tax_other_charge', 'bill_no', 'agent_id', 'destination_id', 'purchase_entry_id', 'destination', 'pnr', 'adults', 'owner_id',
        'infants', 'pax_price', 'travel_date', 'travel_time', 'airline', 'remark', 'created_by', 'display_price', 'child', 'infant_charge', 'child_charge', 
        'booking_source','agent_markup','status','arrival_date','arrival_time','is_refundable','refund_rules','cost_price', 'departureDate', 'arrivalDate'
    ];

    protected $dates = ['departureDate','arrivalDate'];

    protected $casts = [
        'travel_date' => 'datetime'
    ];

    protected function pnr(): Attribute
    {
        return Attribute::make(
            get: function(string $value) {
                $res = json_decode($value, true);
                if ($res !== null) {
                    return implode(",", $res);
                } else {
                    return $value;
                }    
            }
        );
    }

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
        return $this->hasMany('App\Models\FlightTicket\BookTicketSummary');
    }
    public function passenger_details_names()
    {
        return $this->hasMany('App\Models\FlightTicket\BookTicketSummary','book_ticket_id','book_ticket_id');
    }
    public function get_passenger_details_adult()
    {
        return $this->hasMany('App\Models\FlightTicket\BookTicketSummary')->where('type', 1);
    }
    public function get_passenger_details_child()
    {
        return $this->hasMany('App\Models\FlightTicket\BookTicketSummary')->where('type', 2);
    }
    public function get_passenger_details_infants()
    {
        return $this->hasMany('App\Models\FlightTicket\BookTicketSummary')->where('type', 3);
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

    public function comments(){
        return $this->belongsTo('App\Models\SalesNote','id','book_ticket_id')->orderBy('id','DESC');
    }
}
