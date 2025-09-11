@component('mail::message')
# Hello

Namelist for the PNR {{ $ticket_details->pnr }}

@component('mail::table')
    | Information   | Details   |
    | ------------- |:-------------:|
    | Sector       | {{ $ticket_details->destination->name }} |
    | Travel Date | {{ $ticket_details->travel_date->format('d-M-Y')}}  |
    | No of Pax | {{ $ticket_details->quantity }} |
    | PNR | {{ $ticket_details->pnr }}  |
    | Flight no|  {{ $ticket_details->flight_no }} |

@endcomponent

@component('mail::table')
    | #       | Title         | First Name  | Last Name |
    | ------------- |:-------------:| --------:|--------:|
    @foreach($data as $key => $val)
    | {{ 1 +$key }}.   |  {{ $val->title }}      | {{ $val->first_name }}     |  {{ $val->last_name }} |
    @endforeach
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
