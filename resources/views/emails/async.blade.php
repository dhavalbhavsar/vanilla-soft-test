@component('mail::message')
# Introduction

{!! $email->body !!}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
