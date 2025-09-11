<?php

namespace App\Exports;


use App\Models\FlightTicket\Airline;
use App\Models\FlightTicket\Destination;
use App\Models\FlightTicket\Owner;
use App\PurchaseEntry;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NameListExport implements   FromCollection, WithHeadings
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
        $q = PurchaseEntry::orderBy('travel_date','ASC');

        if($this->filters->has('destination_id') && $this->filters->destination_id != '')
            $q->where('destination_id',$this->filters->destination_id);

        if($this->filters->has('pnr_no') && $this->filters->pnr_no != '')
            $q->where('pnr',$this->filters->pnr_no);

        if($this->filters->has('name_list') && $this->filters->name_list != ''){
            $q->whereDate('name_list',Carbon::parse($this->filters->name_list));
        }else{
            $q->whereDate('name_list',Carbon::now());
        }
        if($this->filters->has('travel_date') &&$this->filters->travel_date != ''){
            $q->whereDate('travel_date',Carbon::parse($this->filters->travel_date));
        }

        if($this->filters->has('airline') && $this->filters->airline != '')
            $q->where('airline_id',$this->filters->airline);

        if($this->filters->has('exclude_zero') && $this->filters->exclude_zero != '')
            $q->where(function ($query) {
                $query->where('available', '>', 100)
                    ->orWhere('blocks', '>', 0);
            });

        $data = $q->select('airline_id','destination_id','pnr','quantity','blocks','available','travel_date','departure_time','arrival_time','available','owner_id','name_list')
             ->get();
        foreach($data as $key => $value){
            $value->airline = Airline::find($value->airline_id)->name;
            $value->destination = Destination::find($value->destination_id)->name;
            $value->owner = Owner::find($value->owner_id)->name;
            $value->travel_date1 = ($value->travel_date) ? Carbon::parse($value->travel_date)->format('d-m-Y') : 'NA';
             $value->name_list1   = Carbon::parse($value->name_list)->format('d-m-Y');
            unset($value->airline_id);
            unset($value->travel_date);
            unset($value->name_list);
            unset($value->destination_id);
            unset($value->owner_id);
        }
        return $data;
    }

    public function headings(): array
    {
        return [

            'PNR No.',
            'Qty',
            'Block',
            'Available',

            'DPT',
            'ARV',

            'Airline',
            'Destination',
            'Owner',
            'Travel Date',
            'Name List'

        ];
    }
}
