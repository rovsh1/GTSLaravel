<?php

namespace Module\Booking\Infrastructure\HotelBooking\Adapter;

use Module\Booking\Deprecated\HotelBooking\Adapter\HotelRoomAdapterInterface;
use Module\Catalog\Application\Admin\Room\Find;
use Module\Catalog\Application\Admin\UseCase\GetRooms;

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
