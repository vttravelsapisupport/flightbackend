@component('mail::message')
# Hello {{ $data->first_name }}

we have created your account in VishalTravels


@component('mail::table')
    | Details       |  |
    | ------------- |:-------------:| --------:|
    | username     | {{ $data->phone }}        |
    | email     | {{ $data->email }}        |
    | password     | {{ $password }}        |

@endcomponent
@php
  $url =  url('/login');
@endphp

@component('mail::button', ['url' => $url])
Login
@endcomponent

Best Regards,<br>
{{ config('app.name') }} Teams
@endcomponent
