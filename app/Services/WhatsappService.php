<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WhatsappService
{
    public static function sendOTP($phone,$name,$otp): bool|string
    {
        $curl = curl_init();
        $request =  array(
            CURLOPT_URL => 'https://api.interakt.ai/v1/public/message/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
            "countryCode": "+91",
            "phoneNumber": '.$phone.',
            "callbackData": "http://127.0.0.1:8000/webhook/interakt.php",
            "type": "Template",
            "template": {
                "name": "goflysmart_otp",
                "languageCode": "en",
                "bodyValues": [
                    "'.$name.'",
                    "'.$otp.'"
                ]
            }
        }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic WS04andZSTNRNWZzZmxuWDc3ZlQ2emdkbTRhZGpINTRCWXNoSl9YdXFySTo=',
                'Content-Type: application/json'
            ),
        );
        curl_setopt_array($curl,$request);
        Log::info($request);

        $response = curl_exec($curl);
        Log::info($response);
        return $response;
    }

    public static function sendFlightTicket($phone,$agency_name,$bill_no,$pnr,$url,$file_name): bool|string
    {

        $req = '{
            "countryCode": "+91",
            "phoneNumber": "'.$phone.'",
            "callbackData": "some text here",
            "type": "Template",
            "template": {
                "name": "flight_ticket_gfs_admin",
                "languageCode": "en",
                "headerValues": [
                    "'.$url.'"
                ],
                "fileName": "'.$file_name.'",
                "bodyValues": [
                    "*'.$agency_name.'*",
                    "*'.$bill_no.'*",
                    "*'.$pnr.'*"
                ]
            }
        }';

        $curl = curl_init();
        $request =  array(
            CURLOPT_URL => 'https://api.interakt.ai/v1/public/message/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $req,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic WS04andZSTNRNWZzZmxuWDc3ZlQ2emdkbTRhZGpINTRCWXNoSl9YdXFySTo=',
                'Content-Type: application/json'
            ),
        );
        curl_setopt_array($curl,$request);
        Log::info($request);

        $response = curl_exec($curl);
        Log::info($response);
        return $response;
    }
}
