<?php

namespace App\Exports;


use App\PurchaseEntry;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;

class PurchaseEntryPNRExport implements FromCollection
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

        if($this->filters ->has('destination_id') && $this->filters ->destination_id != '')
            $q->where('destination_id',$this->filters ->destination_id);

        if($this->filters ->has('pnr_no') && $this->filters ->pnr_no != '')
            $q->where('pnr',$this->filters ->pnr_no);

        if($this->filters ->has('owner_id') && $this->filters ->owner_id != '')
            $q->where('owner_id',$this->filters ->owner_id);

        if($this->filters ->has('travel_date_from') && $this->filters ->travel_date_from != ''
            && $this->filters ->has('travel_date_to') && $this->filters ->travel_date_to != '')
        {
            $from = Carbon::parse($this->filters ->travel_date_from)->startOfDay();
            $to   = Carbon::parse($this->filters ->travel_date_to)->endOfDay();
            $q->whereBetween('travel_date',[$from,$to]);
        }

        if($this->filters ->has('airline') && $this->filters ->airline != '')
            $q->where('airline_id',$this->filters ->airline);

        if($this->filters ->has('exclude_zero') && $this->filters ->exclude_zero != '')
            $q->where('quantity','>',0);

        $data = $q->select('pnr')
            ->get();



        return $data;
    }

    public function headings(): array
    {
        return [
            'Pnr'
        ];
    }
}
