<?php

/**
 * Created by PhpStorm.
 * User: deepankarmondal
 * Date: 2019-09-02
 * Time: 01:15
 */

namespace App\Imports;

use App\Models\FlightTicket\Accounts\PnrHistory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class PnrHistoryUpload implements ToCollection, WithHeadingRow
{
    public $airline;

    function __construct($airline) {
        $this->airline = $airline;
    }

    public function collection(Collection $rows)
    {
        $insert = [];
        foreach ($rows as $key => $row)
        {
            $temp = [];
            $temp['payment_date'] = Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(trim($row['payment_date'])))->format('Y-m-d');
            $temp['payment_date_1'] = Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(trim($row['payment_date'])))->format('Y-m-d');
            $temp['pnr'] = $row['pnr'];
            $temp['active_pnr'] = ($row['parentpnr']) ? $row['parentpnr'] : $row['pnr'];
            $temp['passenger_name'] = $row['passenger_name'];
            $temp['amount'] = str_replace(",","",$row['amount']);
            $temp['parent_pnr'] = $row['parentpnr'];
            $temp['created_at'] = $this->airline;
            $temp['airline_code'] = $this->airline;
            $temp['created_at'] = date('Y-m-d H:i:s');
            $temp['updated_at'] = date('Y-m-d H:i:s');

            array_push($insert , $temp);
        }

        foreach(array_chunk($insert, 200) as $value) {
            Pnrhistory::insert($value);
        }

    }
}
