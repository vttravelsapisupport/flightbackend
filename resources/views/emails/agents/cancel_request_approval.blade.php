@component('mail::message')
# Dear {{$agent->company_name}}

Your cancellation request has been approved. Below is the remarks from admin - 
Remarks : {{$message}}

#Cancel Details
@component('mail::table')
    | PNR       | Bill No       | Charge   |  Total Refund | PAX | ADULT | CHILD | INFANT |
    | ----------| ------------- |:--------:| ------------- | --- | ------| ------|--------|
    | {{$bookingTicketDetail->pnr}} | {{$bookingTicketDetail->bill_no}}      |  {{$refundData['pax_cost']}} | {{$refundData['total_refund']}} | {{$refundData['pax']}} | {{$refundData['adult']}} | {{$refundData['child']}} | {{$refundData['infant']}}

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
