<?php

namespace Dainsys\Evaluate\Tests\Feature\Events;

use Dainsys\Evaluate\Models\Rating;
use Dainsys\Evaluate\Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Dainsys\Evaluate\Mail\RatingCreatedMail;
use Dainsys\Evaluate\Events\RatingCreatedEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dainsys\Evaluate\Listeners\SendRatingCreatedMail;

class RatingCreatedEventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function event_is_dispatched()
    {
        Event::fake([
            RatingCreatedEvent::class
        ]);

        $this->evaluateSuperAdminUser();

        $rating = Rating::factory()->create();

        Event::assertDispatched(RatingCreatedEvent::class);
        Event::assertListening(
            RatingCreatedEvent::class,
            SendRatingCreatedMail::class
        );
    }

    /** @test */
    public function when_rating_is_created_an_email_is_sent()
    {
        Mail::fake();

        $this->evaluateSuperAdminUser();

        $rating = Rating::factory()->create();

        Mail::assertQueued(RatingCreatedMail::class);
    }
}
