<?php

namespace App\Models\FlightTicket;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Agent extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'type','code', 'company_name', 'alias', 'contact_name', 'address', 'city', 'state_id',
        'email', 'phone', 'whatsapp', 'gst_no', 'nearest_airport', 'referred_by', 'travel_agent_you_know',
        'opening_balance', 'credit_balance', 'account_manager_id', 'credit_agent', 'account_type_id', 'status','credit_request_status'
        ,'aadhaar_card_no',
        'pan_card_no',
        'isEmailVerified',
        'isPhoneVerified',
        'isGSTVerified',
        'isPANVerified',
        'isAadhaarVerified',
        'isEmailVerified',
        'isPhoneVerified',
        'additional_phone',
        'additional_email',
        'pan_card_url',
        'aadhaar_card_url',
        'gst_url',
        'credit_limit',
        'has_api',
        'zipcode',
        'remarks',
        'credit_shell'
    ];

    protected $casts = [
        'additional_phone' => 'array',
        'additional_email' => 'array',
    ];

    public function account_manager()
    {
        return $this->belongsTo('App\User', 'account_manager_id', 'id');
    }

    public function state()
    {
        return $this->belongsTo('App\Models\State', 'state_id', 'id');
    }
    public function account_type()
    {
        return $this->belongsTo('App\Models\FlightTicket\Accounts\AgentAccountType', 'account_type_id', 'id');
    }

    public function nearestAirportDetails()
    {
        return $this->belongsTo('App\Models\FlightTicket\Airport', 'nearest_airport', '');
    }

    public function getSaleTicket()
    {
        return $this->hasMany('App\Models\FlightTicket\BookTicket', 'agent_id', 'id');
    }

    public function getAccountTransaction()
    {
        return $this->hasMany('App\Models\FlightTicket\Credits', 'agent_id', 'id');
    }

    public function tickets()
    {
        return $this->hasOne('App\Models\FlightTicket\BookTicket')->orderBy('id', 'DESC')
            ->whereMonth('created_at', '>', 3)
            ->whereYear('created_at', '=', 2021);
    }

    public function getCurrentOpeningBalance()
    {
        return $this->belongsTo('App\Models\FlightTicket\Accounts\AgentOpeningBalanceFY', 'id', 'agent_id')->orderBy('id','DESC');
    }

    public function getLatestBooking()
    {
        return $this->belongsTo('App\Models\FlightTicket\BookTicket', 'id', 'agent_id');
    }

    public function getAgentDebitorRemark()
    {
        return $this->belongsTo('App\Models\FlightTicket\Accounts\AgentDebitorRemark', 'id', 'agent_id')->orderBy('id','DESC');
    }

    public function under_distributor(){
        return $this->hasMany('App\Models\FlightTicket\DistributorAgentAlignment','agent_id','id');
    }

    public function getSupplierRestrictionCount(){
        return $this->hasMany('App\Models\FlightTicket\AgentSupplierRestriction','agent_id','id');
    }

    public function getUnflowAmount(){
        return $this->hasMany('App\Models\FlightTicket\BookTicket', 'agent_id', 'id')->whereDate('travel_date','>',Carbon::now());
    }

    public function did_agent_booking_count($from,$to){
        $from =  Carbon::parse($from)->startOfDay();
        $to   =  Carbon::parse($to)->endOfDay();

        $agent_booking_ids = BookTicket::where('agent_id', $this->id)->whereBetween('created_at', [$from,$to])->pluck('id');
        $agent_booking_count = BookTicketSummary::whereIn('book_ticket_id', $agent_booking_ids)->whereIn('type', [1,2])->count();
        $agent_booking_count_infant = BookTicketSummary::whereIn('book_ticket_id', $agent_booking_ids)->whereIn('type', [3])->count();
        return [$agent_booking_count,$agent_booking_count_infant];
    }

    public function did_agent_booking_amount($from,$to){
        $from =  Carbon::parse($from)->startOfDay();
        $to   =  Carbon::parse($to)->endOfDay();

        $agent_booking_count = BookTicket::where('agent_id',$this->id)->whereBetween('created_at', [$from,$to])->get();
        $amount = 0;
        foreach($agent_booking_count as $key => $booking) {
            $amount += $booking->pax_price * ($booking->adults + $booking->child) + $booking->infant_charge * ($booking->infants);
        }
        return $amount;
    }
}
