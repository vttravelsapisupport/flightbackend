<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GFSV2APIService
{
    private $username = "9832500105";
    private $password = "UjXDiS7uIFpPrTC";
    private $api_key = "6f42757eff06475d960c650da67d22d9";
    private $endpoint = 'https://goflysmart-api-v2.azurewebsites.net/api/v2';
    protected function login(): mixed
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->endpoint . '/auth/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                        "username": "' . $this->username . '",
                        "password": "' . $this->password . '"
                    }',
            CURLOPT_HTTPHEADER => array(
                'api-key: ' . $this->api_key,
                'Content-Type: application/json',
                'Accept: application/json, text/plain, */*',
                // '2023-01-01: v2',
                'Origin: https://agent.goflysmart.com'
            ),
        ));


        $response = curl_exec($curl);

        curl_close($curl);

        if($response){

            $data =  json_decode($response);
            Log::info($response);
            if($data->success){
                return $data->access_token;
            }else{
                return $data;
            }
        }else{
            return false;
        }


    }
    private function _search($origin,$destination,$adults,$child,$infant,$departure_date)
    {
        $api_token = $this->login();
        $departure_date = Carbon::parse($departure_date)->format('Y-m-d');
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->endpoint.'/flight/search?adults='.$adults.'&child='.$child.'&infant='.$infant.'&origin='.$origin.'&destination='.$destination.'&departure_date='.$departure_date,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: '.$api_token,
                'api-key: '.$this->api_key,
                'Content-Type: application/json;charset=UTF-8',
                'Accept: application/json, text/plain, */*',
                'Origin: https://agent.goflysmart.com'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        if($response){
            Log::info($response);
            $results = json_decode($response);
            if($results->success) {
                return $results->data;
            }
            return $results;
        }else{
            return $response;
        }

    }
    public function getSearchResult($origin,$destination,$adults,$child,$infant,$departure_date){
        
       return $this->_search($origin,$destination,$adults,$child,$infant,$departure_date);
    }
}
