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
@if($bookTicketDetails->child > 0)
| Childs      | {{ $bookTicketDetails->child}}   |
@endif
@if($bookTicketDetails->infants > 0)
| Infants      | {{ $bookTicketDetails->infants}}   |
@endif
| Destination | {{ $bookTicketDetails->destination}}|
| Adult Price      | {{ $bookTicketDetails->pax_price}}   |
@if($bookTicketDetails->child > 0)
| Child Price      | {{ $bookTicketDetails->child_charge}}   |
@endif
@if($bookTicketDetails->infants > 0)
| Infant Price      | {{ $bookTicketDetails->infant_charge}}   |
@endif
| Total Price      | {{ ($bookTicketDetails->pax_price * $bookTicketDetails->adults) + ($bookTicketDetails->child_charge * $bookTicketDetails->child) + ($bookTicketDetails->infant_charge * $bookTicketDetails->infants) }}   |
| Travel Date      | {{ $bookTicketDetails->travel_date->format('d M Y') }}   |
@endcomponent

@component('mail::table')
| Passenger Information     |
|:-------------|
@foreach($passengerDetails as $key => $value)
|  {{ $passengerDetails[$key]['title'] .' '. $passengerDetails[$key]['first_name'].' '. $passengerDetails[$key]['last_name'] }}   |
@endforeach

@endcomponent

@component('mail::button', ['url' => env('APP_URL').'/print/'.$bookTicketDetails->id])
View Ticket
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
