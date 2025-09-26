<?php

namespace App\Services;

use App\Registry\DestinationRegistry;

class DestinationService
{
   public static function getOrginAndDestinationCodeFromDestinationId($destinationId){
    $destination = DestinationRegistry::get($destinationId);
    $code = $destination['code'];
    $cleanCode = strtoupper(trim($code)); // "IXBDEL"

    // Origin = first 3 chars
    $origin = substr($cleanCode, 0, 3);

    // Destination = last 3 chars
    $destination = substr($cleanCode, -3);
    return  [
        'origin' => $origin,
        'destination' => $destination,
    ];

   }
}