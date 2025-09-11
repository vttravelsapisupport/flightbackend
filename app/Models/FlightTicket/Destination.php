<?php

namespace App\Models\FlightTicket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;
    protected $fillable = ['code','name','destination_id','origin_id','is_international', 'status'];

    public function destination(){
        return $this->belongsTo('App\Models\FlightTicket\Airport');
    }
    public function origin(){
        return $this->belongsTo('App\Models\FlightTicket\Airport');
    }
    public function manager(){
        return $this->hasOne('App\Models\FlightTicket\FlightInventorySummarySectorManager','sector_id','id');
    }
    
}
