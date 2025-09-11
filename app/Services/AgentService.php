<?php

namespace App\Services;


use App\Models\AgentPayment;
use App\Models\FlightTicket\Accounts\Receipt;
use App\Models\FlightTicket\Agent;

class AgentService
{
    public static function updateCreditshell($agent_id,$amount){
        return Agent::where('id',$agent_id)->increment('credit_shell',$amount);
    }

    private static function getAgentBalance($Agentid)
    {
        $agent = Agent::find($Agentid);
        $total_balance = 0;
        if ($agent->opening_balance > 0) {
            $total_balance = $total_balance + $agent->opening_balance;
            $total_balance = $total_balance + $agent->credit_balance;
        } else {
            $total_balance = $total_balance + $agent->credit_balance;
        }
        return $total_balance;
    }

    public function getAllActiveAgents(){
        return Agent::where('status',1)
            ->select('company_name','id','phone','code','opening_balance')
            ->get();
    }

    public function getCurrentAgentBalance($Agentid){
        $agent = Agent::find($Agentid);
        $total_balance = 0;
        if($agent->opening_balance > 0){
            $total_balance = $total_balance + $agent->opening_balance;
            $total_balance = $total_balance + $agent->credit_balance;
        }else{
            $total_balance = $total_balance + $agent->credit_balance;
        }
        return $total_balance;
    }

    public function updateAgentOpeningBalance($agent_id,$type,$amount){
        $agent =  Agent::where('id',$agent_id)->first();
        $credit_balance = $agent->credit_balance;
        $balance        = $agent->opening_balance;
        if($type == '1'){
            $amount = $agent->opening_balance + $amount;
            if($agent->opening_balance < 0) {
                $credit_balance = $agent->credit_limit + abs($amount);
            }
        }
        elseif($type == '2'){
            $balance = $agent->opening_balance - $amount;
            if($agent->opening_balance >= $amount){
                $balance = $agent->opening_balance - $amount;
                $credit_balance = $agent->credit_balance;
            }elseif($agent->opening_balance < $amount && $agent->opening_balance > 0){
                $balance    = $agent->opening_balance - $amount;
                $credit_balance = ($agent->credit_balance > 0 ) ?  $agent->credit_balance - abs($balance) : $agent->credit_balance;
            }elseif($agent->opening_balance < 0){
                $balance        = $agent->opening_balance - $amount;
                $credit_balance = ($agent->credit_balance > 0 ) ? $agent->credit_balance - $amount : $agent->credit_balance;
            }
        } elseif($type == '6'){
            $balance = $agent->opening_balance - $amount;
            if($agent->opening_balance >= $amount){
                $balance = $agent->opening_balance - $amount;
                $credit_balance = $agent->credit_balance;
            }elseif($agent->opening_balance < $amount && $agent->opening_balance > 0){
                $balance    = $agent->opening_balance - $amount;
                $credit_balance = ($agent->credit_balance > 0 ) ?  $agent->credit_balance - abs($balance) : $agent->credit_balance;
            }elseif($agent->opening_balance <= 0){
                $balance        = $agent->opening_balance - $amount;
                $credit_balance = ($agent->credit_balance > 0 ) ? $agent->credit_balance - $amount : $agent->credit_balance;
            }
        }

        return $agent->update([
            'opening_balance' => $balance,
            'credit_balance' => $credit_balance
        ]);
    }

    public static function updateOpeningBalance($agent_id, $type, $amount)
    {

        $agent =  Agent::where('id', $agent_id)->first();

        if ($type == '1') // credit
            $amount = $agent->opening_balance + (float) $amount;
        elseif ($type == '2') // debit
            $amount = $agent->opening_balance -  (float)$amount;
        elseif ($type == '5') // temporary debit
            $amount = $agent->opening_balance -  (float)$amount;

        return $agent->update([
            'opening_balance' => $amount
        ]);
    }



    public static function updateCreditBalance($agent_id, $type, $amount)
    {

        $agent =  Agent::where('id', $agent_id)->first();
        if ($type == '5') { // temporary debit
            $credit_balance = $agent->credit_balance - $amount;
            $opening_balance = $agent->opening_balance;
        }
        if ($type == '1') { // temporary credit
            $credit_balance = $agent->credit_balance + $amount;
            $opening_balance = $agent->opening_balance;
        }

        if ($type == '7') { // distibutor balance add to balance
            $credit_balance = $agent->credit_balance;
            $opening_balance = $agent->opening_balance + $amount;
        }

        return $agent->update([
            'credit_balance' => $credit_balance,
            'opening_balance' => $opening_balance
        ]);
    }
    public static function generatePaymentReceiptNo(){
        $credits = AgentPayment::select('id')->orderBy('id','DESC');

        if($credits->count() == 0){
            $serial_no = str_pad(1, 6, "0", STR_PAD_LEFT);
            return strtoupper('AGPAY-'.$serial_no);
        }else{
            $serial_no = str_pad($credits->first()->id+1, 6, "0", STR_PAD_LEFT);
            return strtoupper('AGPAY-'.$serial_no);
        }
    }


    public static function updateCreditBalanceBasedOnCreditLimt($agent_id, $type, $amount)
    {
        $agent =  Agent::where('id', $agent_id)->first();
        $credit_balance = $agent->credit_balance;
        $balance        = $agent->opening_balance;
        $credit_limit   = $agent->credit_limit;

        if ($type == '1') { // credit
            if($balance < 0) {
                $credit_balance = $credit_balance + (float)$amount;
            } else {
                $credit_balance = $credit_limit;
            }
        }

        return $agent->update([
            'credit_balance' => $credit_balance
        ]);
    }
}
