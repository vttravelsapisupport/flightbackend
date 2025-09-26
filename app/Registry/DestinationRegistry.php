<?php
namespace App\Registry;


use Illuminate\Support\Facades\Cache;
use App\Models\FlightTicket\Destination;

class DestinationRegistry
{
    protected static array $destinations = [];

    public static function load(): void
    {
        // Load from Redis cache
        self::$destinations = Cache::rememberForever('destinations', function () {
            return Destination::all()
                ->keyBy('id') // e.g. 'DEL' => [array]
                ->toArray();
        });
    }

    public static function all(): array
    {
        if (empty(self::$destinations)) {
            self::load();
        }
        return self::$destinations;
    }

    public static function get(string $code): ?array
    {
        if (empty(self::$destinations)) {
            self::load();
        }
        return self::$destinations[$code] ?? null;
    }

    public static function refresh(): void
    {
        Cache::forget('destinations');
        self::load();
    }
}
