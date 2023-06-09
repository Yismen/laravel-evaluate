@component('mail::message')
# Hello,

Ticket #{{ $ticket->reference }}, created by {{ $ticket->owner->name }} {{ $ticket->created_at->diffForHumans() }}, has
been {{ $ticket->status->name }} by {{ $user?->name }}!.

@if (strlen($comment) > 0)
> Comment: *"{{ $comment }}"*
@endif

**Title: {{ $ticket->subject->name }}**

*Content:*
> {!! $ticket->description !!}

<x-evaluate::email.button :url="route('evaluate.my_tickets', ['ticket_details' => $ticket->id])">View
    Ticket</x-evaluate::email.button>

Thanks,<br>
{{ config('app.name') }}
@endcomponent