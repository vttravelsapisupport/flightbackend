<?php

namespace App\Models\FlightTicket;

use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightInventorySummarySectorManager extends Model {

    use HasFactory;

    protected $table = 'flight_inventory_summary_sector_managers';
    protected $fillable = ['manager_id', 'sector_id'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }   
    public function manager() {
        return $this->belongsTo(User::class);
    }    

    public function destination() {
        return $this->belongsTo(Destination::class, 'destination_id', 'id');
    }
}

