<style>
    ul {
  list-style-type: none; /* Remove bullets */
  padding: 0; /* Remove padding */
  margin: 0; /* Remove margins */
}
</style>
<div class="table-responsive">
    @if($data)
    <ul >
        <li>
            <strong>Bill No</strong>
          <a href="{{  route('sales.show',$data->id)}}" target="_blank">
            {{ $data->bill_no }}</a>  
        </li>
        <li>
            <strong>Booking Date</strong>
            {{ $data->created_at->format('d-m-y h:i:s') }}
        </li>
        <li>
            <strong>Travel Date</strong>
            {{ $data->travel_date->format('d-m-Y') }}
        </li>
        <li>
            <strong>Sector</strong>
            {{ $data->destination }}
        </li>
      
        <li>
            <strong>Amount</strong>
            @php
                        $total_adult = $data->adults + $data->child;
                        $total_adult_price =  $total_adult  * $data->pax_price;
                        $total_infants = $data->infants;
                        $total_infants_price =  $total_infants  * $data->infant_charge;

                        $total =  $total_adult_price +  $total_infants_price;
                    @endphp
            Rs. {{ $total }}
        </li>
        <li>
            <strong>Vendor</strong>
            {{ $data->purchase_entry->owner->name}}
        </li>
        <li>
        <a href="{{ route('sales-reports.index',[
            'result' =>100,
            'travel_date_from' => $travel_date_from,
            'travel_date_to' => $travel_date_to,
            'agent_id'=> $agent_id
            ]) }}"
            target="_blank"
             > View All </a>
        </li>
    </ul>
    @else
    <ul>
        <li>No Record found</li>
    </ul>
    @endif
</div>
