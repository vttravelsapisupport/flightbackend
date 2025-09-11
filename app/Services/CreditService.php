<?php

namespace App\Services;

use App\Models\FlightTicket\Credits;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CreditService
{
    public  static function generateReferenceNo(){
        $credits = Credits::select('id')->orderBy('id','DESC');
        if($credits->count() == 0){
            $number  = str_pad('1', 6, "0", STR_PAD_LEFT);
            return strtoupper('TEMP-'.$number);
        }
        else{
            $number  = str_pad($credits->first()->id+1, 6, "0", STR_PAD_LEFT);
            return strtoupper('TEMP-'.$number);
        }
    }



    public  function createCreditRecord($agent_id,$type,$amount,$ticket_id,$remarks,$date){
        $old_credit = Credits::orderBy('id','DESC')->where('agent_id',$agent_id)->first();
        $creditType =  $this->creditType($type);

        $opening_balance = $old_credit->opening_balance . $creditType->operator . $amount;

        $data = [
            'agent_id' =>$agent_id,
            'type' => $type,
            'amount' =>$amount,
            'owner_id' => Auth::id(),
            'reference_no' => CreditService::generateReferenceNo(),
            'ticket_id' => $ticket_id,
            'opening_balance' => $old_credit,
            'remarks' => $remarks,
            'created_at' => ($date)? Carbon::parse($date): Carbon::now()
        ];
        Credits::create($data);
    }

    private function creditType($type){
        // Temporary Credit
        if($type == 1)
            return  (object)  [
                'type' => 'credit',
                'operator' => '+'
            ];
        //Sales
        if($type == 2)
            return  (object) [
                'type' => ' debit',
                'operator' => '-'
            ];


        //Receipt
        if($type == 3)
            return  (object) [
                'type' => ' debit',
                'operator' => '+'
            ];
        //Refund
        if($type == 4)
            return  (object) [
                'type' => ' debit',
                'operator' => '+'
            ];

        // Temporary Debit
        if($type == 5)
            return  (object) [
                'type' => 'credit',
                'operator' => '-'
            ];


        //Additional Services
        if($type == 6)
            return  (object) [
                'type' => 'debit',
                'operator' => '-'
            ];

    }
}
