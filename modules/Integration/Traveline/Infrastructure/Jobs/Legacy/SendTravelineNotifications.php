<?php

declare(strict_types=1);

namespace Module\Integration\Traveline\Infrastructure\Jobs\Legacy;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Module\Integration\Traveline\Domain\Adapter\TravelineAdapterInterface;
use Module\Integration\Traveline\Infrastructure\Adapter\TravelineAdapter;
use Module\Integration\Traveline\Infrastructure\Models\Legacy\TravelineReservation;

class SendTravelineNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        if (!TravelineReservation::whereNull('accepted_at')->exists()) {
            return;
        }
        $travelineAdapter = $this->getTravelineAdapter();
        $travelineAdapter->sendReservationNotification();
    }

    private function getTravelineAdapter(): TravelineAdapterInterface
    {
        $notificationsUrl = config('modules.Traveline.notifications_url');

        return new TravelineAdapter(new Client(['verify' => false]), $notificationsUrl);
    }
}
