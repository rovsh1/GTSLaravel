<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use Module\Booking\HotelBooking\Application\UseCase\Admin\GetStatuses;
use Module\Booking\HotelBooking\Application\UseCase\Admin\GetStatusHistory;
use Module\Booking\HotelBooking\Application\UseCase\Admin\UpdateBookingStatus;

class StatusAdapter
{
    public function getStatuses(): array
    {
        return app(GetStatuses::class)->execute();
    }

    public function updateStatus(
        int $id,
        int $status,
        ?string $notConfirmedReason = null,
        ?float $cancelFeeAmount = null
    ): mixed {
        return app(UpdateBookingStatus::class)->execute($id, $status, $notConfirmedReason, $cancelFeeAmount);
    }

    public function getStatusHistory(int $id): array
    {
        return app(GetStatusHistory::class)->execute($id);
    }
}
