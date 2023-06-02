<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use Module\Booking\Hotel\Application\UseCase\GetAvailableActions;

class BookingAdapter
{
    public function getAvailableActions(int $id): mixed
    {
        return app(GetAvailableActions::class)->execute($id);
    }
}
