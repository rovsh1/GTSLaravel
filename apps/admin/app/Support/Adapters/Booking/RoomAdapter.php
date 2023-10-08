<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use App\Core\Support\Adapters\AbstractHotelAdapter;
use Module\Booking\Application\Admin\HotelBooking\UseCase\GetAvailableRooms;

class RoomAdapter extends AbstractHotelAdapter
{
    public function getAvailableRooms(int $bookingId): array
    {
        return app(GetAvailableRooms::class)->execute($bookingId);
    }
}
