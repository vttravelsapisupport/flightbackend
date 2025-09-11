<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

class PurchaseEntry extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected  $table = 'purchase_entries';

    protected $fillable = [
        'child_charge',  'infant_charge', 'child', 'infant', 'markup_price',
        'namelist_status', 'destination_id', 'airline_id', 'base_price', 'tax',
        'arrival_date', 'is_domestic', 'pnr', 'flight_no', 'price_type',
        'travel_date', 'name_list', 'name_list_day', 'departure_time', 'arrival_time', 'quantity',
        'available', 'sold', 'blocks', 'cost_price', 'sell_price', 'markup_fprice', 'owner_id',
        'purchase_entry_id', 'flight_route', 'isOnline', 'isRefundable','baggage_info', 'segments'
    ];

    protected $dates = ['travel_date', 'name_list', 'arrival_date','name_list_timestamp'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d');
    }

    public function airline()
    {
        return $this->belongsTo('App\Models\FlightTicket\Airline');
    }

    public function destination()
    {
        return $this->belongsTo('App\Models\FlightTicket\Destination');
    }
    public function owner()
    {
        return $this->belongsTo('App\Models\FlightTicket\Owner');
    }
    public function status(){
        return $this->hasMany('App\Models\FlightTicket\PurchaseEntryStatus','purchase_entry_id','id');
    }
    public function flightStatus(){
        return $this->belongsTo('App\Models\FlightTicket\PurchaseEntryStatus','id','purchase_entry_id')->orderBy('id','DESC');
    }

    public function  price_log(){
        return $this->hasMany('App\Models\FlightTicket\PurchaseTicketFareLog','purchase_entry_id','id');
    }

    public function vendor(){
        return $this->belongsTo('App\Models\FlightTicket\Owner','owner_id','id');
    }

    public function fareRules()
    {
        return $this->hasMany('App\Models\FlightTicket\TicketFareRule');
    }
}
