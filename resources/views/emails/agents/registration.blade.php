@component('mail::message')
# Dear {{ $user->first_name }}

you have been register in our portal as Agent
@component('mail::table')
    | Details       | Description   |
    | ------------- |:-------------:|
    | Company Name      | {{ $agent->company_name }}     |
    | Contact Name      | {{ $agent->contact_name }}|
    | Address      | {{ $agent->address }}|
    | City      | {{ $agent->city }}|
    | State      | {{ $agent->state->state }}|
    | Phone      | {{ $agent->phone }}|
    | Whatsapp      | {{ $agent->whatsapp }}|
    | GST      | {{ $agent->gst_no ? $agent->gst_no : 'NA' }}|
    | Nearest Airport      | {{ $agent->nearest_airport }}|
    @if($agent->referred_by)
    | Referred By      | {{ $agent->referred_by }}|
    @endif
    @if($agent->monthly_business)
    | Monthly Business      | {{ $agent->monthly_business }}|
    @endif
    @if($agent->credit_limit)
    | Credit Limit      | {{ $agent->credit_limit }}|
    @endif
    @if($agent->opening_balance)
    | Opening Balance      | {{ $agent->opening_balance }}|
    @endif
    | Account Manager      | {{ $agent->account_manager->first_name }} {{ $agent->account_manager->last_name }} - {{ $agent->account_manager->phone }}|

@endcomponent
#Login Details
@component('mail::table')
    | Details       | Description   |
    | ------------- |:-------------:|
    | Username      | {{ $user->phone }} |
    | Password      | {{ $password }}|
@endcomponent

@component('mail::button', ['url' => url('login')])
Click here to login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
