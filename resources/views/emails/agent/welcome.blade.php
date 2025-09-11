@component('mail::message')
# hello {{ $agent->company_name}}

Your account is registered with us.

@component('mail::table')
| Details       | Info         |
| ------------- |:-------------:|
| Url     |   Vishal Travels  |
| Username     |   {{ $user->email }}  |
| Password     |  {{ $password }} |
@endcomponent
@php
 $link = '';
@endphp
@component('mail::button', ['url' => $link ])
Reset Password
@endcomponent
 
Thanks,<br>
{{ config('app.name') }}
@endcomponent
