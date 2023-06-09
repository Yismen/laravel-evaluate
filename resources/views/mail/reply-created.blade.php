@component('mail::message')
# Hello,

{{ $user?->name }} has replied on ticket #{{ $reply->ticket->reference }} with the following message:

> *"{{ $reply->content }}"*

<x-evaluate::email.button :url="route('evaluate.my_tickets', ['ticket_details' => $reply->ticket->id])">
    View
    Ticket</x-evaluate::email.button>

Thanks,<br>
{{ config('app.name') }}
@endcomponent