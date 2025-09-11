@component('mail::message')
# Hello {{ $data->agent->company_name }}

{{$content}}


@component('mail::table')
| PNR NO| Booked On       | Reference No.| Price  |
|  :-------------: | :-------------: |:-------------:| :--------:|
|  {{$data->purchase_entry->pnr}}     |{{ $data->created_at->format('d-m-y h:i:s') }}     | {{ $data->bill_no }}      | Rs. {{ $data->pax_price * $data->adults  }}    |
@endcomponent

@component('mail::table')
| Flight | Departure | Arrival  |
| :-------------|:-------------:| :--------: | --------: |
|{{$data->purchase_entry->airline->name }} |{{$data->purchase_entry->destination->origin->name }} ({{$data->purchase_entry->destination->origin->code}})  | {{$data->purchase_entry->destination->destination->name}} ({{$data->purchase_entry->destination->destination->code}})|
| ({{$data->purchase_entry->flight_no}} )|  {{$data->purchase_entry->travel_date->format('d-m-Y')}} {{$data->purchase_entry->departure_time}} |{{$data->purchase_entry->travel_date->format('d-m-Y')}} {{$data->purchase_entry->arrival_time}}|

 


@endcomponent


Passenger Details
@php
$i = 1;
@endphp

@component('mail::table') 
| #      | Name |  Type |
| ------------- |:-------------:|:-------------:|
@foreach($data->get_passenger_details_adult as $key => $val)
| {{ $i }}      | {{  $val->title }} {{  $val->first_name }} {{  $val->last_name }}  |  Adult
@php 
$i++
@endphp
@endforeach
@foreach($data->get_passenger_details_child as $key => $val)
| {{ $i }}      | {{  $val->title }} {{  $val->first_name }} {{  $val->last_name }}  | Child
@php 
$i++
@endphp
@endforeach
@foreach($data->get_passenger_details_infants as $key => $val)
| {{ $i }}      | {{  $val->title }} {{  $val->first_name }} {{  $val->last_name }}  | Infant
@php 
$i++
@endphp
@endforeach

@endcomponent



Thanks,<br>
{{ config('app.name') }}
@endcomponent
