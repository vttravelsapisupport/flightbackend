<?php


namespace App\Services;



use App\Models\FlightTicket\Accounts\SupplierTransaction;

class SupplierService
{
    public  static function generateReferenceNo(){
        $credits = SupplierTransaction::select('id')->orderBy('id','DESC');
        if($credits->count() == 0){
            $number  = str_pad('1', 6, "0", STR_PAD_LEFT);
            return strtoupper('TEMP-'.$number);
        }
        else{
            $number  = str_pad($credits->first()->id+1, 6, "0", STR_PAD_LEFT);
            return strtoupper('TEMP-'.$number);
        }
    }



    private function transactionType($type){
        //Sales
        if($type == 1)
            return  (object) [
                'type' => ' credit',
                'operator' => '+'
            ];

        //Refund
        if($type == 2)
            return  (object) [
                'type' => ' debit',
                'operator' => '-'
            ];


        //Additional Services
        if($type == 3)
            return  (object) [
                'type' => 'credit',
                'operator' => '+'
            ];

    }
}
