<?php

namespace App\Services;
use App\Models\FlightTicket\BookTicket;
use App\Models\FlightTicket\TicketFareRule;
use App\PurchaseEntry;
use App\Models\FlightTicket\AirlineCancellationPolicy;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RefundService {

 
    public static function ticketHasFareRules($id) {
        $purchase = PurchaseEntry::find($id);
        return $purchase->fareRules;
    }


    public static function insertTicketFareRule($purchase) {
        $airlineCancellationPolicy = AirlineCancellationPolicy::where('airline_id', $purchase->airline_id)->get();
        foreach($airlineCancellationPolicy as $policy) {
            $amount =  0;
            $originCountryCode = $purchase->destination->origin->countryCode;
            $destinationCountryCode = $purchase->destination->destination->countryCode;
            if($originCountryCode == 'IN' && $destinationCountryCode == 'IN') {
                $amount = $policy->amount;
            }else{
                $amount = $policy->int_amount;
            }
            if($amount ==  0) {
                continue;
            }
            $cancellationSlot = $policy->cancellationSlots;
            TicketFareRule::create([
                'purchase_entry_id'    => $purchase->id,
                'slot_id'              => $cancellationSlot->id,
                'slot_desc'            => $cancellationSlot->name,
                'duration'             => $cancellationSlot->slot,
                'amount'               => $amount,
                'user_id'              => Auth::user()->id
            ]);
        }
    }


    public static function getAutoCancellationCharge($book_id) {
        $bookTicket      = BookTicket::find($book_id);
        $purchase        = PurchaseEntry::find($bookTicket->purchase_entry_id);
        $start           = Carbon::now();
        $end             = new Carbon($bookTicket->travel_date->format('Y-m-d').' '.$bookTicket->travel_time);
        $hour            = $start->diffInHours($end);
        $charge          = 0;
        $per_pax_markup  = 0;

        if($bookTicket->agent_markup) {
            $per_pax_markup = $bookTicket->agent_markup / ($bookTicket->adults + $bookTicket->child);
        }

        $pax_price = $bookTicket->pax_price + $per_pax_markup;

        if(!$purchase->isRefundable) {
            return $charge;
        }

        if($hour <= 24) {
            $charge = $pax_price;
        }

        foreach($purchase->fareRules as $rule) {
            if($hour >= $rule->duration) {
                if($purchase->destination->is_international) {
                    $charge =  $rule->international_amount;
                }else{
                    $charge =  $rule->domestic_amount;
                }
                break;
            }
        }

        if($charge > $pax_price) {
            $charge = $pax_price;
        }
        
        return $charge;
    }

}