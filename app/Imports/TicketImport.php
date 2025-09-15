<?php

/**
 * Created by PhpStorm.
 * User: deepankarmondal
 * Date: 2019-09-02
 * Time: 01:15
 */

namespace App\Imports;

use Exception;
use Throwable;
use Carbon\Carbon;
use App\PurchaseEntry;
use App\Services\FlightService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\FlightTicket\Airline;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\FlightTicket\Destination;
use Maatwebsite\Excel\Concerns\Importable;


class TicketImport implements ToModel
{
    use Importable;
    protected static $buffer = [];
    protected static $batchSize = 200;


    public function model(array $row)
    {

        ini_set('memory_limit', '1024M');

        static $airlines = null;
        static $destinations = null;

        if ($airlines === null) {
            // build map: code => id
            $airlines = Airline::pluck('id', 'code')->toArray();
            $destinations = Destination::pluck('id', 'code')->toArray();
        }


        if ($row[0]) {
            try {
                if(!is_numeric ( $row[3] )){
                    throw new Exception("Flight No. should be numeric");
                }

                $travel_date  = Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[4]));

                $name_list_date = Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[4]))->subDays($row[13]);
                $airlineId = $airlines[trim($row[1])] ?? null;
                $destinationId = $destinations[trim($row[0])] ?? null;
                if(!$destinationId){
                   throw new Exception("Destination is not available ".$row[0]);
                }

                $start_time =  strtotime(Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(trim($row[5])))->format('H:i'));
                $end_time   =  strtotime(Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(trim($row[6])))->format('H:i'));

                if($start_time < $end_time) {
                    $arrival_date = date('Y-m-d', strtotime(trim($travel_date)));
                }else{
                    $arrival_date = date('Y-m-d', strtotime(trim($travel_date). ' + 1 days'));
                }
                $pnr_filtered = preg_replace("/[^a-zA-Z0-9]+/", "", trim($row[2]));

                $baggage_info = [
                    'cabin_baggage'         => !empty($row[14]) ? $row[14] . 'KG' : null,
                    'checkin_baggage'       => !empty($row[15]) ? $row[15] . 'KG' : null,
                    'cabin_baggage_count'   => !empty($row[16]) ? (int)$row[16] : null,
                    'checkin_baggage_count' => !empty($row[17]) ? (int)$row[17] : null,
                ];
                $flight_no = trim($row[1]).' '.trim($row[3]);

                $data = [
                    'destination_id'     => $destinationId,
                    'airline_id'    => $airlineId,
                    'pnr' => $pnr_filtered,
                    'flight_no' => $flight_no,
                    'travel_date' => trim($travel_date),
                    'arrival_date' => trim($arrival_date),
                    'name_list' => trim($name_list_date),
                    'departure_time' => Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(trim($row[5])))->format('H:i'),
                    'arrival_time' => Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(trim($row[6])))->format('H:i'),
                    'quantity' => trim($row[7]),
                    'available' => trim($row[7]),
                    'cost_price' => trim($row[8]),
                    'child' => trim($row[9]),
                    'infant' => trim($row[10]),
                    'sell_price' => trim($row[9]),
                    'owner_id' => trim($row[11]),
                    'flight_route' => trim($row[12]),
                    'name_list_day' => trim($row[13]),
                    'purchase_entry_id' => Auth::id(),
                    'isOnline' => 1, // offliine
                    'baggage_info' => json_encode($baggage_info),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                self::$buffer[] = $data;

                // If buffer is full, flush to DB
                if (count(self::$buffer) >= self::$batchSize) {
                    $this->flushBuffer();
                }


            } catch (Throwable $e) {
                Log::info($e);
                report($e);
                throw $e;
            }
        }
    }

    protected function flushBuffer()
    {
        if (empty(self::$buffer)) {
            return;
        }

        $retries = 5;
        $delay = 100000; // 100ms

        for ($i = 0; $i < $retries; $i++) {
            try {
                DB::transaction(function () {
                    PurchaseEntry::insert(self::$buffer);
                });
                self::$buffer = [];
                return;
            } catch (\Illuminate\Database\QueryException $e) {
                if (in_array($e->errorInfo[1] ?? null, [1213, 40001]) && $i < $retries - 1) {
                    usleep($delay); // wait before retry
                    $delay *= 2; // exponential backoff
                    continue;
                }
                Log::error("Bulk insert failed", ['error' => $e->getMessage()]);
                throw $e;
            }
        }
    }

    public function __destruct()
    {
        $this->flushBuffer();
    }

    public function chunkSize(): int
    {
        return 200; // process 100 rows at a time
    }
}
