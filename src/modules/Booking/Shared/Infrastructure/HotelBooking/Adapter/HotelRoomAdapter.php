<?php

namespace Module\Booking\Shared\Infrastructure\HotelBooking\Adapter;

use Module\Booking\Shared\Domain\Booking\Adapter\HotelRoomAdapterInterface;
use Module\Hotel\Moderation\Application\Admin\Response\RoomDto;
use Module\Hotel\Moderation\Application\Admin\Room\Find;
use Module\Hotel\Moderation\Application\Admin\UseCase\GetRooms;

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
