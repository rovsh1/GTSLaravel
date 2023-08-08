<?php

declare(strict_types=1);

namespace Module\Integration\Traveline\Infrastructure\Jobs\Legacy;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Module\Integration\Traveline\Domain\Adapter\TravelineAdapterInterface;
use Module\Integration\Traveline\Infrastructure\Models\Legacy\TravelineReservation;

class SendTravelineNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly TravelineAdapterInterface $travelineAdapter
    ) {}

    public function handle(): void
    {
        if (!TravelineReservation::whereNull('accepted_at')->exists()) {
            return;
        }
        $this->travelineAdapter->sendReservationNotification();
    }

}
