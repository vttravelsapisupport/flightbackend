<?php

namespace App\Models\FlightTicket;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory;
    public function sales_count($from,$to){
        $from =  Carbon::parse($from)->startOfDay();
        $to   =  Carbon::parse($to)->endOfDay();

        $agent_bookings = BookTicket::join('purchase_entries','purchase_entries.id','=','book_tickets.purchase_entry_id')
                                            ->where('purchase_entries.owner_id',$this->id)
                                            ->whereBetween('book_tickets.created_at', [$from,$to])
                                            ->select('book_tickets.adults', 'book_tickets.child')
                                            ->get();
        $count = 0;
        foreach($agent_bookings as $key => $booking) {
            $count += $booking->adults  + $booking->child;
        }
        return $count;
    }

    public function sales_amount($from,$to){

        $from =  Carbon::parse($from)->startOfDay();
        $to   =  Carbon::parse($to)->endOfDay();

        $agent_booking_count = BookTicket::join('purchase_entries','purchase_entries.id','=','book_tickets.purchase_entry_id')
            ->where('purchase_entries.owner_id',$this->id)
            ->whereBetween('book_tickets.created_at', [$from,$to])
            ->select('book_tickets.adults', 'book_tickets.child','book_tickets.pax_price','book_tickets.infant_charge','book_tickets.infants')
            ->get();
        $amount = 0;
        foreach($agent_booking_count as $key => $booking) {
            $amount += $booking->pax_price * ($booking->adults + $booking->child) + $booking->infant_charge * ($booking->infants);
        }
        return $amount;
    }
}
