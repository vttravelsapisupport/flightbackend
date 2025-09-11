<?php

namespace App\Services;

use App\Models\FlightTicket\Accounts\Receipt;


class ReceiptService
{
    public static function generateReceiptNo(){

        $credits = Receipt::select('id')->orderBy('id','DESC');
        if($credits->count() == 0){
            $serial_no = str_pad(1, 6, "0", STR_PAD_LEFT);
            return strtoupper('RCPT-'.$serial_no);
        }else{
            $serial_no = str_pad($credits->first()->id+1, 6, "0", STR_PAD_LEFT);
            return strtoupper('RCPT-'.$serial_no);
        }
    }
}
