<?php

namespace App\Services;

class FlightApiService
{
    private $isAuthenticated;
    private $token;

    private $username;
    private $password;

    private $endpoint = 'https://goflysmartapi.azure-api.net/prod/v2';

    private function login()
    {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://goflysmartapi.azure-api.net/prod/v2/auth/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "username": "9832500105",
                "password": "0E0j2Ezf4rv7HUV"
            }',
            CURLOPT_HTTPHEADER => array(
                'api-key: f9585d0a9a8448eabd255c1e78602957',
                'Content-Type: application/json;charset=UTF-8',
                'Accept: application/json, text/plain, */*',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;

    }
}
