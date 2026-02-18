<?php

namespace App\Console\Commands;

use App\Models\Registration;
use App\Notifications\ReviewRequestNotification;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class SendReviewRequests extends Command
{
    protected $signature = 'reviews:send-requests';

    protected $description = 'Send review request emails for finished events.';

    public function handle(): int
    {
        $now = now();

        Registration::query()
            ->with('event')
            ->where('status', 'REGISTERED')
            ->where('is_attending', true)
            ->whereNull('review_request_sent_at')
            ->whereDoesntHave('review')
            ->whereHas('event', function (Builder $query) use ($now): void {
                $query->where(function (Builder $query) use ($now): void {
                    $query
                        ->where('date_end', '<', $now)
                        ->orWhere(function (Builder $query) use ($now): void {
                            $query->whereNull('date_end')->where('date_start', '<', $now);
                        });
                });
            })
            ->chunkById(200, function ($registrations): void {
                foreach ($registrations as $registration) {
                    $registration->notify(new ReviewRequestNotification($registration));
                    $registration->forceFill(['review_request_sent_at' => now()])->save();
                }
            });

        $this->info('Review requests sent.');

        return self::SUCCESS;
    }
}
