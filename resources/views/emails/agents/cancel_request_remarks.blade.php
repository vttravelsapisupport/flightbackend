@component('mail::message')
# Dear {{$agent->company_name}} 

{{ $heading }}

Remarks : {{$content}}

@component('mail::table')
| Info       | Details        |
| ------------- |:-------------|
| Agency Code      |    {{ $data->agent->code }}   |
| Bill NO      | {{ $bookingTicketDetail->bill_no}}   |
| PNR | {{ $bookingTicketDetail->pnr }}|
| Date      | {{ date('d-M-Y h:i:s', strtotime($data->created_at)) }}   |

@endcomponent

#Pax Details
@component('mail::table')
    | #  | PAX NAME | 
    | ---|:--------:|
    @foreach($booking_passengers as $key => $value)
    | {{$key+1}} |  {{$value->title}} {{$value->first_name}} {{$value->last_name}}| 
    @endforeach
@endcomponent



Thanks,<br>
{{ $data->owner->first_name }}
@endcomponent
