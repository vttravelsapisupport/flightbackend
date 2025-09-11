<?php
/**
 * Created by PhpStorm.
 * User: deepankarmondal
 * Date: 2019-09-02
 * Time: 02:33
 */

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SupplierLedgerExport implements FromCollection, WithHeadings
{
    protected $result;
    public function __construct($result)
    {

        $this->result = $result;

    }

    public function collection()
    {
        $data = $this->result;
        $res = [];



        foreach($data as $key => $value){
            $temp = [];
            $temp['date'] = $value->created_at->format('d-m-Y h:i:s');
            if ($value->type == 1)
                $temp['order_type'] = 'Air Ticket';
            elseif($value->type == 2)
                $temp['order_type'] = 'Refund';
            elseif($value->type == 3 )
                $temp['order_type'] = 'Additional Services';
            elseif($value->type == 9 )
                $temp['order_type'] = 'Payment';

            $temp['ref_no'] = $value->ticket ? $value->ticket->bill_no : null;
            $temp['tarvel_date'] = $value->ticket ? date('d-m-Y', strtotime($value->ticket->travel_date)) : null;
            $temp['sector'] = $value->ticket ? $value->ticket->destination : null;
            $temp['pnr'] = $value->ticket ? $value->ticket->pnr : null;
            $temp['pnr'] = $value->ticket ? $value->ticket->airline : null;
            $temp['pax'] = $value->ticket ? $value->ticket->adults +  $value->ticket->child + $value->ticket->infants : null;
            if($value->ticket){
                $total_pax_count = count($value->ticket->passenger_details);
                if (count($value->ticket->passenger_details) <= 1){
                    $temp['pax_name'] = $value->ticket->passenger_details[0]->first_name . ' ' . $value->ticket->passenger_details[0]->last_name;
                }else {
                    $temp['pax_name'] = $value->ticket->passenger_details[0]->first_name . ' ' . $value->ticket->passenger_details[0]->last_name . ' + ' . ($total_pax_count - 1) ;
                }
            }else{
                $temp['pax_name'] = '';
            }

            if ($value->type == 2 || $value->type == 9) {
                $temp['debit'] = $value->amount;
            }else{
                $temp['debit'] = '';
            }

            if ($value->type == 1 || $value->type == 3) {
                $temp['credit'] = $value->amount;
            }else{
                $temp['credit'] = '';
            }

            $temp['balance'] = $value->balance;
            $temp['desc'] = $value->remarks;
            $temp['medium'] = $value->payment_mode;
            $res[] = $temp;
        }

        return collect($res);
    }

    public function headings(): array
    {
        return [
            'Date',
            'Order Type',
            'Ref No',
            'Travel Date',
            'Sector',
            'PNR',
            'Airline',
            'No Of Pax',
            'Pax Name',
            'Debit',
            'Credit',
            'Balance',
            'Desc',
            'Medium'
        ];
    }
}
