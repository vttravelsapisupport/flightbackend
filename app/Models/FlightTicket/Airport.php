<?php

namespace App\Models\FlightTicket;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    protected  $fillable = [
        'code',
        'name',
        'cityCode',
        'cityName',
        'countryCode',
        'countryName',
        'timezone',
        'lat',
        'lon',
        'numAirports',
        'city',
        'status'
    ];

    public static function getAirports()
    {
        return DB::table('airports')
                    ->select('name' , 'code','cityCode','cityName','countryName','countryCode')
                    ->distinct('code')
                    ->get();
    }
}
