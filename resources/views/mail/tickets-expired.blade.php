@component('mail::message')
# Hello,

Please see attached a report with all expired tickets. Please review and work accordingly.

<x-evaluate::email.button :url="route('evaluate.dashboard')"> Dashboard </x-evaluate::email.button>

Thanks,<br>
{{ config('app.name') }}
@endcomponent