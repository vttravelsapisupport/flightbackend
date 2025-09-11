<?php
/**
 * Created by PhpStorm.
 * User: deepankarmondal
 * Date: 2019-09-02
 * Time: 02:33
 */

namespace App\Exports;

use App\Models\FlightTicket\Airline;
use App\Models\FlightTicket\Destination;
use App\Models\FlightTicket\Owner;
use App\PurchaseEntry;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BookTicketExport implements FromCollection, WithHeadings
{
    protected $filters;
    public function __construct($filters)
    {

        $this->filters = $filters;

    }

    public function collection()
    {
        $q = PurchaseEntry::orderBy('travel_date','ASC');

        // added filters - arghadip evil inc.

        if ($this->filters->has('owner_id') && $this->filters->owner_id != '')
                $q->where('purchase_entries.owner_id', $this->filters->owner_id);

            if ($this->filters->has('supplier_id') && $this->filters->supplier_id != '')
                $q->where('purchase_entries.owner_id', $this->filters->supplier_id);


            if ($this->filters->has('flight_no') && $this->filters->flight_no != '')
                $q->where('purchase_entries.flight_no', $this->filters->flight_no);

            if ($this->filters->has('namelist_status_id') && $this->filters->namelist_status_id != ''){
                $name_list_id = $this->filters->namelist_status_id;
                if($name_list_id == 4){
                    $name_list_id = 0;
                }
                $q->where('purchase_entries.namelist_status', $name_list_id);
            }

            if ($this->filters->has('airport_id') && $this->filters->airport_id != ''){
                $destination_id = Destination::where('origin_id',$this->filters->airport_id)->where('status',1)->pluck('id')->all();
                $q->whereIn('purchase_entries.destination_id',$destination_id);
            }elseif($this->filters->has('destination_id') && $this->filters->destination_id != ''){
                $q->where('purchase_entries.destination_id', $this->filters->destination_id);
            }

            if ($this->filters->has('pnr_no') && $this->filters->pnr_no != '')
                $q->where('pnr','like','%'. $this->filters->pnr_no.'%');

            if ($this->filters->has('travel_date_to') && $this->filters->travel_date_to != '') {
                $from = Carbon::parse($this->filters->travel_date_from);
                $to  = Carbon::parse($this->filters->travel_date_to);
                $q->whereBetween('purchase_entries.travel_date', [$from, $to]);
            } else {
                $this->filters->session()->forget(['previous_day_date', 'next_day_date']);
            }

            if ($this->filters->has('namelist_date') && $this->filters->namelist_date != '') {
                $namelist_date = Carbon::parse($this->filters->namelist_date);
                $q->whereDate('purchase_entries.name_list', $namelist_date);
            }

            if ($this->filters->has('destination_order') && $this->filters->destination_order == 'asc' || $this->filters->destination_order == 'desc') {
                $q->orderby('d.name', $this->filters->destination_order);

            }

            if ($this->filters->has('available_order') && $this->filters->available_order == 'asc' || $this->filters->available_order == 'desc') {
                $q->orderBy("available", $this->filters->available_order);
            }

            if ($this->filters->has('exclude_zero') && $this->filters->exclude_zero != '') {
                $q->where(function ($query) {
                    $query->where('purchase_entries.available', '>', 0)
                        ->orWhere('purchase_entries.blocks', '>', 0);
                });
            }

            if ($this->filters->has('over_booking') && $this->filters->over_booking != '') {
                $q->where('quantity','<','sold');
            }
            if ($this->filters->has('show_zero') && $this->filters->show_zero != '') {
                $q->where(function ($query) {
                    $query->where('purchase_entries.available',  0)
                        ->Where('purchase_entries.blocks',0);
                });
            }
            if($this->filters->has('airline') && $this->filters->airline != ''){
                $q->where('purchase_entries.airline_id', $this->filters->airline);
            }
            if ($this->filters->has('type') && $this->filters->type != '') {
                $q->where('purchase_entries.isOnline', $this->filters->type);
            }


        if($this->filters->has('destination_id') && $this->filters->destination_id != '')
            $q->where('destination_id',$this->filters->destination_id);
        if($this->filters ->has('pnr_no') && $this->filters->pnr_no != '')
            $q->where('pnr',$this->filters ->pnr_no);
        if($this->filters ->has('travel_date_to') && $this->filters->travel_date_to != ''){
            $from = Carbon::parse($this->filters ->travel_date_from);
            $to  = Carbon::parse($this->filters ->travel_date_to);

            if($this->filters->has('next_day') && $this->filters->next_day != ''){
                $to = $to->addDay();
                $this->filters ->travel_date_to = $to;
            }
            if($this->filters->has('previous_day') && $this->filters->previous_day != ''){
                $from = $from->subDay();
                $this->filters ->travel_date_from = $from;
            }
            $q->whereBetween('travel_date',[$from,$to]);
        }
        if($this->filters->has('airline') && $this->filters->airline != '')
            $q->where('airline_id',$this->filters ->airline);

        if($this->filters->has('exclude_zero') && $this->filters->exclude_zero != ''){
            $q->where('available','>',0);
            $q->orWhere('blocks','>',0);
        }

        $data = $q->select('airline_id','destination_id','pnr','flight_no','travel_date','name_list','departure_time','arrival_time','quantity','available','blocks','cost_price','sell_price','owner_id','flight_route')
                  ->orderBy('travel_date', 'ASC')->get();

        foreach($data as $key => $value){
            $value->airline = Airline::find($value->airline_id)->name;
            $value->destination = Destination::find($value->destination_id)->name;
            $value->owner = Owner::find($value->owner_id)->name;
            $value->available = $value->available . ' (' . $value->quantity . ')';
            unset($value->quantity);
            unset($value->airline_id);
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
            'Available',
            'Blocks',
            'Cost Price',
            'Sell Price',
            'Flight Route',
            'Airline',
            'Destination',
            'Owner',
        ];
    }
}
