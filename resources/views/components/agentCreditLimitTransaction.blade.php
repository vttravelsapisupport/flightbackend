<style>
    ul {
  list-style-type: none; /* Remove bullets */
  padding: 0; /* Remove padding */
  margin: 0; /* Remove margins */
}
</style>

@if($data)
<ul>

    <li>
        <strong>Date</strong>
        {{ $data['created_at']->format('d-m-Y h:i:s')}}
    </li>
    <li>
        <strong>Old</strong>
        <ul>
            @foreach($data['old_values'] as $key1 => $val1)
            @if($key1 == 'credit_limit')
                <li> <b>{{ $key1 }} </b>{{ $val1 }} </li>
                @endif
            @endforeach
        </ul>
       
    </li>
    <li>
        <strong>New</strong>
        <ul>
            @foreach($data['new_values'] as $key1 => $val1)
            @if($key1 == 'credit_limit')
                <li> <b>{{ $key1 }} </b>{{ $val1 }} </li>
            @endif    
            @endforeach
        </ul>
    </li>
    <li>
        <strong>ACtion</strong>
        {{ $data['user'] }}
    </li>
    @else
 
</ul>
@endif
No Record Found
