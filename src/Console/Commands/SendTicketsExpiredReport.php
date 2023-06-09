<?php

namespace Dainsys\Evaluate\Console\Commands;

use Illuminate\Console\Command;
use Dainsys\Evaluate\Models\Ticket;
use Illuminate\Support\Facades\Mail;
use Dainsys\Evaluate\Mail\TicketsExpiredMail;
use Dainsys\Evaluate\Services\RecipientsService;

class SendTicketsExpiredReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'evaluate:send-tickets-expired-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a report with the tickets that are expired!';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(RecipientsService $recipientsService): int
    {
        $tickets = Ticket::query()
            ->incompleted()
            ->expired()
            ->orderBy('expected_at', 'ASC')
            ->with([
                'subject',
                'department',
                'owner',
                'agent',
            ])
            ->get();

        if ($tickets->count() > 0) {
            Mail::to($recipientsService->superAdmins()->allDepartmentAdmins()->get())
                    ->send(new TicketsExpiredMail($tickets));

            $this->info("Report Sent with {$tickets->count()} tickets");
        }

        return 0;
    }
}
