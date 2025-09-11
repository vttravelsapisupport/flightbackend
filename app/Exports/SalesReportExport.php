<?php

/**
 * Created by PhpStorm.
 * User: deepankarmondal
 * Date: 2019-09-02
 * Time: 02:33
 */

namespace App\Exports;

use App\Models\FlightTicket\Airline;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\FlightTicket\BookTicket;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class SalesReportExport implements FromCollection, WithHeadings
{
    protected $filters;
    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    
    public function collection()
    {
        $query =    "SELECT
        BT.id,  BT.agent_id, BT.bill_no, BT.pnr, BT.destination_id, BT.destination, D.code as destination_code, BT.travel_date, BT.adults, BT.infants, BT.pax_price, BT.travel_time, BT.airline, BT.created_at, BT.remark, BT.purchase_entry_id, BT.child , BT.child_charge,
        A.company_name, A.code,
        S.name as state_name,
        PE.flight_no,
        O.name as owner_name,O.is_third_party
        FROM book_tickets BT , agents A, states S, purchase_entries PE, owners O, destinations D
        WHERE BT.agent_id = A.id AND
              BT.purchase_entry_id = PE.id AND
              BT.destination_id = D.id AND
              A.state_id = S.id AND
              O.id = PE.owner_id AND
              BT.deleted_at IS NULL ";

        if ($this->filters->has('agent_id') && $this->filters->agent_id != '') {
            $query.= "AND BT.agent_id = '".$this->filters->agent_id."' ";
        }

        if ($this->filters->has('destination_id') && $this->filters->destination_id != '') {
            $query.= "AND BT.destination_id = '".$this->filters->destination_id."' ";
        }

        if ($this->filters->has('bill_no') && $this->filters->bill_no != '') {
            $query.= "AND BT.bill_no = '".$this->filters->bill_no."' ";
        }

        if ($this->filters->has('travel_date') && $this->filters->travel_date != '') {
            $travel_date = Carbon::parse($this->filters->travel_date);

            $travel_date = date('Y-m-d', strtotime($travel_date));
            $query.= "AND BT.travel_date = '".$travel_date."' ";
        }

        if ($this->filters->has('airline') && $this->filters->airline != '') {
            $airline_name = Airline::find($this->filters->airline)->name;
            $query.= "AND BT.airline = '".$airline_name."' ";
        }

        if ($this->filters->has('pnr_no') && $this->filters->pnr_no != '') {
            $query.= "AND BT.pnr_no = '".$this->filters->pnr_no."' ";
        }

        if ($this->filters->has('owner_id') && $this->filters->owner_id != '') {
            $query.= "AND PE.owner_id = '".$this->filters->owner_id."' ";
        }

        if ($this->filters->has('supplier_id') && $this->filters->supplier_id != '') {
            $query.= "AND PE.owner_id = '".$this->filters->supplier_id."' ";
        }

        if ($this->filters->has('from') && $this->filters->from != '') {
            $from = Carbon::parse($this->filters->from);
            $from = date('Y-m-d', strtotime($from));
            $to   = Carbon::parse($this->filters->to);
            $to = date('Y-m-d', strtotime($to));

            $query.= "AND DATE(BT.created_at) BETWEEN '".$from."' AND '".$to."' ";
        }

        $data = DB::select(DB::raw($query));

        if(!empty($data)) {
            foreach ($data as $key => $value) {
                $value->total_price = ($value->pax_price * $value->adults) + ($value->child_charge * $value->child);
                $value->agent = $value->company_name;
                $value->agent_code  = $value->code;
                $value->adults =      $value->adults + $value->child;
                $value->state = $value->state_name;
                $value->passenger_name = '';
                $value->travel_date_formated = Carbon::parse($value->travel_date)->format('d-M-y');
                $value->passenger_name = $this->get_Ticket_Details($value->id);
                $value->supplier = $value->owner_name ." ". ($value->is_third_party == 1 ? " - (Third Party)" : null);

                unset($value->agent_id);
                unset($value->infants);
                unset($value->travel_date);
                unset($value->id);
                unset($value->purchase_entry_id);
                unset($value->destination_id);
                unset($value->child);
                unset($value->infant_charge);
                unset($value->child_charge);
                unset($value->company_name);
                unset($value->code);
                unset($value->state_name);
                unset($value->owner_name);
                unset($value->is_third_party);

            }
        }

        return collect($data);
    }


    function get_Ticket_Details($id)
    {
        $query = "SELECT first_name,last_name from book_ticket_details WHERE book_ticket_id=$id ";
        $data = DB::select(DB::raw($query));

        $list = '';
        foreach ($data as $value) {
            $list .= $value->first_name . ' ' . $value->last_name . ',';
            break;
        }

        return  rtrim($list, ',');
    }



    public function headings(): array
    {
        return [
            'Bill No',
            'PNR',
            'Destination',
            'DestinationCode',
            'Adults',
            'Pax Price',
            'Travel Time',
            'Airline',
            'Booking Date and Time',
            'Remarks',
            'Flight No',
            'Total Price',
            'Agency Name',
            'Agency ID',
            'State',
            'Pax Name',
            'Travel Date',
            'Vendor'
        ];
    }
}
