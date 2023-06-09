<?php

namespace Dainsys\Evaluate\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Dainsys\Evaluate\Models\Reply;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReplyCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public Reply $reply;

    public function __construct(Reply $reply)
    {
        $this->reply = $reply;
    }

    public function build()
    {
        $ticket = $this->reply->ticket;

        return $this
            ->subject("Ticket #{$ticket->reference} Has Been Replied")
            ->priority($ticket->mail_priority)
            ->markdown('evaluate::mail.reply-created', [
                'user' => $this->reply?->user
            ])

        ;
    }
}
