<?php

namespace App\Exports;

use App\Models\FlightTicket\AirTicketRefunds;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AirTicketRefundExport implements FromCollection,  WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $filters;
    public function __construct($filters)
    {

        $this->filters = $filters;

    }

    public function collection()
    {
        $filter = $this->filters;

        $q  = AirTicketRefunds::join('book_tickets','book_tickets.id','=','air_ticket_refunds.book_ticket_id')
        ->join('agents','agents.id','=','air_ticket_refunds.agent_id')
        ->join('destinations','destinations.id','=','book_tickets.destination_id')
        ->join('purchase_entries','purchase_entries.id','=','book_tickets.purchase_entry_id')
        ->join('owners','owners.id','=','purchase_entries.owner_id')
        ->join('users','users.id','=','air_ticket_refunds.owner_id');


        if($this->filters->has('agent_id') && $this->filters->agent_id != ''){
            $q->where('air_ticket_refunds.agent_id', $this->filters->agent_id);
        }

        if($this->filters->has('owner_id') && $this->filters->owner_id != ''){
            $q->where('owners.id', $this->filters->owner_id);
        }

        //bill no
        if($this->filters->has('bill_no') && $this->filters->bill_no != '')
        {

            $q->whereHas('bookTicket', function($query) use ($q,$filter){
                $query->where('bill_no', '=',  $this->filters->bill_no);
            });
        }
        //destination
        if($this->filters->has('destination_id') && $this->filters->destination_id != ''){
            $q->whereHas('bookTicket', function($query) use ($q,$filter){
                $query->where('destination_id', '=',  $this->filters->destination_id);
            });
        }
        //travel_date
        if($this->filters->has('travel_date') && $this->filters->travel_date != ''){
            $q->whereHas('bookTicket', function($query) use ($q,$filter){
                $query->whereDate('travel_date',Carbon::parse($this->filters->travel_date));
            });
        }
        //airline
        if($this->filters->has('airline') && $this->filters->airline != ''){
            $q->whereHas('bookTicket', function($query) use ($q,$filter){
                $query->where('airline',$this->filters->airline);
            });
        }
        // pnr
        if($this->filters->has('pnr_no') && $this->filters->pnr_no != ''){
            $q->whereHas('bookTicket', function($query) use ($q,$filter){
                $query->where('pnr',$this->filters->pnr_no);
            });
        }

        // created date and time range
        if($this->filters->has('from') && $this->filters->from != '' && $this->filters->has('to') && $this->filters->to != ''){
            $from = Carbon::parse($this->filters->from);
            $to   = Carbon::parse($this->filters->to)->endOfDay();
            $q->whereBetween('air_ticket_refunds.created_at',[$from,$to]);
        }

        $datas = $q->select('owners.name as owner_name','air_ticket_refunds.created_at as refund_created_at','air_ticket_refunds.total_refund','agents.company_name as agency_name','book_tickets.airline as airline_name','book_tickets.travel_date as travel_date',
        'book_tickets.bill_no', 'book_tickets.pnr', 'air_ticket_refunds.adult',
        'air_ticket_refunds.child','air_ticket_refunds.infant', 'book_tickets.pax_price','book_tickets.infant_charge','air_ticket_refunds.pax','air_ticket_refunds.pax_cost','users.first_name','air_ticket_refunds.remarks','destinations.name as destination_name'
        )->orderBy('air_ticket_refunds.id','DESC')->get();

        $row = [];
        foreach($datas as $value) {
            $tmp = [];
            $tmp['agency'] = $value->agency_name;
            $tmp['owner'] = $value->owner_name;
            $tmp['destination'] = $value->destination_name;
            $tmp['airline'] = $value->airline_name;
            $tmp['travel_date'] = Carbon::parse($value->travel_date)->format('d-M-Y');
            $tmp['bill_no'] = $value->bill_no;
            $tmp['pnr'] = $value->pnr;
            $tmp['pax'] = $value->adult + $value->child;
            $tmp['infant'] = $value->infant;
            $tmp['fare'] = $value->pax_price;
            $tmp['charge'] = $value->pax_cost;
            $tmp['refund_pp'] = $value->pax_price - $value->pax_cost;
            $tmp['total_refund'] = $value->total_refund;
            $tmp['infant_refund'] = $value->infant * $value->infant_charge;
            $tmp['refund_date'] = Carbon::parse($value->refund_created_at)->format('d-m-Y H:i:s');
            $tmp['user'] = $value->first_name;
            $tmp['remarks'] = $value->remarks;

            array_push($row, $tmp);
        }

        return collect($row);

    }

    public function headings(): array
    {
        return [
            'Agency Name',
            'Supplier',
            'Sector',
            'Airline',
            'Travel Date',
            'Bill No',
            'PNR No',
            'PAX',
            'Infant',
            'Fare',
            'Charge',
            'Refund PP',
            'Total Refund',
            'Infant Refund',
            'Refund Date & Time',
            'User',
            'Remarks'
        ];
    }
}
