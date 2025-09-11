<?php

namespace App\Exports;

use App\Models\FlightTicket\Airline;
use App\Models\FlightTicket\Destination;
use App\Models\FlightTicket\Owner;
use App\PurchaseEntry;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TicketExport implements FromCollection, WithHeadings
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
        $q  =  PurchaseEntry::orderBy('travel_date','ASC');

        // added filters - arghadip evil inc.

        if ($this->filters->has('destination_id') && $this->filters->destination_id != '')
            $q->where('purchase_entries.destination_id', $this->filters->destination_id);

        if ($this->filters->has('entry_date') && $this->filters->entry_date != '')
            $q->whereDate('purchase_entries.created_at', Carbon::parse($this->filters->entry_date));

        if ($this->filters->has('flight_no') && $this->filters->flight_no != '')
            $q->where('purchase_entries.flight_no', $this->filters->flight_no);

        if ($this->filters->has('owner_id') && $this->filters->owner_id != '')
            $q->where('purchase_entries.owner_id', $this->filters->owner_id);

        if ($this->filters->has('supplier_id') && $this->filters->supplier_id != '')
            $q->where('purchase_entries.owner_id', $this->filters->supplier_id);

        if ($this->filters->has('pnr_no') && $this->filters->pnr_no != '')
            $q->where('purchase_entries.pnr', $this->filters->pnr_no);

        if ($this->filters->has('travel_date_from') && $this->filters->travel_date_from != '' && $this->filters->has('travel_date_to') && $this->filters->travel_date_to != '') {
            $from = Carbon::parse($this->filters->travel_date_from);
            $to   = Carbon::parse($this->filters->travel_date_to);
            $q->whereBetween('purchase_entries.travel_date', [$from, $to]);
        }

        if ($this->filters->has('airline') && $this->filters->airline != '')
            $q->where('purchase_entries.airline_id', $this->filters->airline);

        if ($this->filters->has('exclude_zero') && $this->filters->exclude_zero != '')
            $q->where('purchase_entries.quantity', '>', 0);

        $data = $q->select('created_at','airline_id','destination_id','pnr','flight_no','travel_date','name_list','departure_time','arrival_time','quantity','cost_price','sell_price','owner_id','flight_route')
            ->get();

        foreach($data as $key => $value){
            $value->airline = Airline::find($value->airline_id)->name;
            $value->destination = Destination::find($value->destination_id)->name;
            $value->owner = Owner::find($value->owner_id)->name;
            $value->entry_date = Carbon::parse($value->created_at)->format('d-m-Y');
            unset($value->airline_id);
            unset($value->created_at);
            unset($value->destination_id);
            unset($value->owner_id);
        }
        return $data;
    }

    public function headings(): array
    {
        return [
            'PNR',
            'Flight No',
            'Travel Date',
            'Name List',
            'Departure Time',
            'Arrival Time',
            'Quantity',
            'Cost Price',
            'Sell Price',
            'Flight Route',
            'Airline',
            'Destination',
            'Owner',
            'Entry Date'
        ];
    }
}
