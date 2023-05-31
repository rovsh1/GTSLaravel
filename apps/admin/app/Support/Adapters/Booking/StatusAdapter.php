<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use Module\Booking\Common\Application\UseCase\GetAvailableStatuses;
use Module\Booking\Common\Application\UseCase\GetStatuses;
use Module\Booking\Common\Application\UseCase\UpdateBookingStatus;

class StatusAdapter
{
    public function getStatuses(): array
    {
        return app(GetStatuses::class)->execute();
    }

    public function getAvailableStatuses(int $id): array
    {
        return app(GetAvailableStatuses::class)->execute($id);
    }

    public function updateStatus(int $id, int $status): void
    {
        app(UpdateBookingStatus::class)->execute($id, $status);
    }
}
