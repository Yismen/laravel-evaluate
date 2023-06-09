@component('mail::message')
# Hello,

{{ $user->name }} has rated service on ticket #{{ $rating->ticket->reference }}@if ($rating->ticket->agent), attended
by **{{ $rating->ticket->agent->name
}}**, @endif as **{{ str($rating->score->name)->headline() }}**.

@if ($rating->comment)
Comment:
> "*{{ $rating->comment }}*"
@endif

<x-evaluate::email.button :url="route('evaluate.my_tickets', ['ticket_details' => $rating->ticket->id])">
    View
    Ticket</x-evaluate::email.button>

Thanks,<br>
{{ config('app.name') }}
@endcomponent