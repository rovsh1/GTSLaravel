<?php

namespace Module\Booking\Infrastructure\HotelBooking\Adapter;

use Module\Booking\Domain\Booking\Adapter\HotelRoomAdapterInterface;
use Module\Catalog\Application\Admin\Response\RoomDto;
use Module\Catalog\Application\Admin\Room\Find;
use Module\Catalog\Application\Admin\UseCase\GetRooms;

class HotelRoomAdapter implements HotelRoomAdapterInterface
{
    public function findById(int $id): ?RoomDto
    {
        return app(Find::class)->execute($id);
    }

    public function getByHotelId(int $hotelId): array
    {
        return app(GetRooms::class)->execute($hotelId);
    }
}
