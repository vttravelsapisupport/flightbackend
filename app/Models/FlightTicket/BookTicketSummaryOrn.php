<?php

namespace App\Models\FlightTicket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class BookTicketSummaryOrn extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'book_ticket_details_orn';

    protected $fillable = [
        'book_ticket_id',
        'title',
        'type',
        'travelling_with',
        'first_name',
        'last_name',
        'dob',
        'pnr', 'is_refund', 'status',
    ];
    protected $dates = ['dob'];


    public function book_ticket()
    {
        return $this->belongsTo('App\Models\FlightTicket\BookTicketOrn');
    }

}
