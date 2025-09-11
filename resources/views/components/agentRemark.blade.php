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
            {{ $data->created_at->format('d-m-y h:i:s') }}
        </li>
        <li>
            <strong>User</strong>
            {{ $data->user->first_name }} {{ $data->user->last_name }}
           
        </li>
        <li>
            <strong>Remark</strong>
            {{ $data->remarks }}
        </li>
        <li>
        <a href="{{ route('debitor-remarks',[
           
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
