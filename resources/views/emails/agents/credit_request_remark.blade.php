@component('mail::message')
# Introduction

{{$content}}


Thanks,<br>
{{ $data->owner }}
@endcomponent
