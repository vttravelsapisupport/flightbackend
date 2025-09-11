@component('mail::message')
# Hello

Ticket has been Booked 

@component('mail::table')
| Info       | Details        |
| ------------- |:-------------|
| PNR      |    {{ $bookTicketDetails->pnr}}   |
| Flight No      |    {{ $bookTicketDetails->purchase_entry->flight_no }}   |
| Flight Route      |    {{ $bookTicketDetails->purchase_entry->flight_route }}   |
| Adults      | {{ $bookTicketDetails->adults}}   |
| Destination | {{ $bookTicketDetails->destination}}|
| Pax Price      | {{ $bookTicketDetails->pax_price}}   |
| Total Price      | {{ $bookTicketDetails->pax_price * $bookTicketDetails->adults }}   |
| Travel Date      | {{ $bookTicketDetails->travel_date->format('d M Y') }}   |
@endcomponent

@component('mail::table')
| Passenger Information     |
|:-------------|
@foreach($passengerDetails as $key => $value)
|  {{ $passengerDetails[$key]['title'] .' '. $passengerDetails[$key]['first_name'].' '. $passengerDetails[$key]['last_name'] }}   |
@endforeach

@endcomponent


Thanks,<br>
{{ config('app.name') }}
@endcomponent
