<?php

namespace App\Services;

use App\Models\FlightTicket\AirlineBaggageInfo;
use App\Models\FlightTicket\AirlineSectorBaggageInfo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FlightService
{
    public static function getBaggageInfo($code){
        $baggageInfo = AirlineBaggageInfo::where('airline_code', $code)->get();
        if(count($baggageInfo) > 0) {
            return $baggageInfo;
        }
        return null;
    }

    public static function getSectorAirlineBaggageInfo($airline_code, $sector_code){
        $baggageInfo = AirlineSectorBaggageInfo::where('airline_code', $airline_code)->where('sector_code', $sector_code)->get();
        if(count($baggageInfo) > 0) {
            return $baggageInfo;
        }
        return null;
    }
}