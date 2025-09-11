<?php

namespace App\Models\FlightTicket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleTicketIntimation extends Model
{
    protected $fillable = [
        'purchase_entry_id',
        'book_ticket_id',
        'subject',
        'content',
        'agent_comment',
        'internal_comment',
        'remarks',
        'user_id',
        'status'
    ];

    public function AgentIntimationRemarks(){
        return $this->hasMany('App\Models\FlightTicket\IntimationRemarks','initimation_id','id')->where('type',1);
    }

    public function bookTicket(){
        return $this->belongsTo('App\Models\FlightTicket\BookTicket');
    }

    public function AllRemarks(){
        return $this->hasMany('App\Models\FlightTicket\IntimationRemarks','initimation_id','id');
    }
    public function InternalIntimationRemarks(){
        return $this->hasMany('App\Models\FlightTicket\IntimationRemarks','initimation_id','id')->where('type',2);
    }
    public function AgentIntimationRemarksOne(){
        return $this->belongsTo('App\Models\FlightTicket\IntimationRemarks','id','initimation_id')->orderBy('id','DESC')->where('type',1);
    }
    public function InternalIntimationRemarksOne(){
        return $this->belongsTo('App\Models\FlightTicket\IntimationRemarks','id','initimation_id')->orderBy('id','DESC')->where('type',2);
    }
    public function user(){
        return $this->belongsTo('App\User');
    }
}
