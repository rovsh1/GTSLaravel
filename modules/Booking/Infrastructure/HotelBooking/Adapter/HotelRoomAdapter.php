<?php

namespace Module\Booking\Infrastructure\HotelBooking\Adapter;

use Module\Booking\Domain\HotelBooking\Adapter\HotelRoomAdapterInterface;
use Module\Hotel\Application\UseCase\GetRooms;
use Module\Hotel\Application\UseCase\Room\Find;

class HotelRoomAdapter implements HotelRoomAdapterInterface
{
    public function findById(int $id): mixed
    {
        return app(Find::class)->execute($id);
    }

    public function getByHotelId(int $hotelId): array
    {
        return app(GetRooms::class)->execute($hotelId);
    }
}
