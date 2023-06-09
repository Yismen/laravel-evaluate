@component('mail::message')
# Hello,

A new ticket with #{{ $ticket->reference }} has been created by {{ $ticket->owner->name }} form department {{
$ticket->department->name }}!

**Title: {{ $ticket->subject->name }}**

*Content:*
> {!! $ticket->description !!}

<x-evaluate::email.button :url="route('evaluate.my_tickets', ['ticket_details' => $ticket->id])">View Ticket
</x-evaluate::email.button>
Thanks,<br>
{{ config('app.name') }}
@endcomponent