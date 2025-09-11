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
            <strong>Date</strong>
            {{ $data->created_at->format('d-m-Y h:i:s')}}
        </li>
        <li>
            <strong>Type</strong>
            @if ($data->type == 1)
                Temporary Credit
            @elseif($data->type == 5)
              Temporary Debit
            @elseif($data->type == 7)
             Distributor Balance
            @endif
        </li>
        <li>
            <strong>Amount</strong>
            @money($data->amount)
        </li>
      
        <li>
            <strong>Remarks</strong>
            {{ $data->remarks }}
        </li>
        <li>
            <strong>Action By</strong>
            {{ $data->owner->first_name }} {{ $data->owner->last_name }}
        </li>
        <li>
        <a href="{{ route('credits-debits.index',
            ['result' =>10000,'agent_id'=> $agent_id,'start_date' => Carbon\Carbon::now()->format('d-m-Y') ,'end_date' => Carbon\Carbon::now()->subDays(365)->format('d-m-Y')]
            ) }}"
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
            