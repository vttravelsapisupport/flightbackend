<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Registry\DestinationRegistry;


class FlightPurchaseEntryService
{
    Public static function clearSearchResultForCache($purchaseEntryList)
    {
        $keysToDeleteFromCache = [];
        foreach($purchaseEntryList as $key => $pe){
              $sector = DestinationService::getOrginAndDestinationCodeFromDestinationId($pe->destination_id);

              $parts = [
                  $sector['origin'],
                  $sector['destination'],
                  $pe->travel_date->format('d-m-Y'),
              ];
             $keysToDeleteFromCache[] = 'search_results_' . implode('_', $parts);
        }
        Log::info('clearning cache for '. implode(',',$keysToDeleteFromCache));
        DB::table('cache')->whereIn('key', $keysToDeleteFromCache)->delete();
    }
}