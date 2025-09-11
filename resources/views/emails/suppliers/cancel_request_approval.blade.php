@component('mail::message')
# Dear {{$owner->name}}

A Ticket refund request has been approved and refunded by admin below are the details.  

#Cancel Details
@component('mail::table')
    | Bill No       | Charge   |  Total Refund | PAX | ADULT | CHILD | INFANT |
    | ------------- |:--------:| ------------- | --- | ------| ------|--------|
    | {{$bookingTicketDetail->bill_no}}      |  {{$refundData['pax_cost']}} | {{$refundData['total_refund']}} | {{$refundData['pax']}} | {{$refundData['adult']}} | {{$refundData['child']}} | {{$refundData['infant']}}

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
{{ config('app.name') }}
@endcomponent
