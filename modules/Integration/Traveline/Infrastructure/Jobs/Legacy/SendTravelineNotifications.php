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

    public function handle(TravelineAdapterInterface $travelineAdapter): void
    {
        \Log::debug('[SendTravelineNotifications] Start');
        if (!TravelineReservation::whereNull('accepted_at')->exists()) {
            return;
        }
        $travelineAdapter->sendReservationNotification();
        \Log::debug('[SendTravelineNotifications] End');
    }

}
