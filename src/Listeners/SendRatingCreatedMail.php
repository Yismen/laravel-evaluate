<?php

namespace Dainsys\Evaluate\Listeners;

use Dainsys\Evaluate\Models\Rating;
use Illuminate\Support\Facades\Mail;
use Dainsys\Evaluate\Mail\RatingCreatedMail;
use Dainsys\Evaluate\Events\RatingCreatedEvent;
use Dainsys\Evaluate\Services\RecipientsService;

class SendRatingCreatedMail
{
    protected Rating $rating;
    protected RecipientsService $recipientsService;

    public function __construct()
    {
        $this->recipientsService = new RecipientsService();
    }

    public function handle(RatingCreatedEvent $event)
    {
        $this->rating = $event->rating;

        $recipients = $this->recipients();

        if ($recipients->count()) {
            Mail::to($recipients)
                ->send(new RatingCreatedMail($this->rating));
        }
    }

    protected function recipients()
    {
        return $this->recipientsService
            ->ofTicket($this->rating->ticket)
            ->superAdmins()
            ->owner()
            ->agent()
            ->departmentAdmins()
            ->get();
    }
}
