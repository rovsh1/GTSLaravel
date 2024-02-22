<?php

namespace Module\Booking\Shared\Infrastructure\Adapter;

use Module\Booking\Shared\Domain\Booking\Adapter\HotelRoomAdapterInterface;
use Module\Hotel\Moderation\Application\Dto\RoomDto;
use Module\Hotel\Moderation\Application\UseCase\FindRoom;
use Module\Hotel\Moderation\Application\UseCase\GetRooms;

class HotelRoomAdapter implements HotelRoomAdapterInterface
{
    public function findById(int $id): ?RoomDto
    {
        return app(FindRoom::class)->execute($id);
    }

    public function getByHotelId(int $hotelId): array
    {
        return app(GetRooms::class)->execute($hotelId);
    }
}
