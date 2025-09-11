<?php


namespace App\Services;

class SMSService
{

    public static function sendSMS($phone_number, $message)
    {

        $message = urlencode($message);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&send_to=91' . $phone_number . '&msg=' . $message . '&msg_type=TEXT&userid=2000212407&auth_scheme=plain&password=E*Y9pQkL&v=1.1&format=json',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;

    }
}
