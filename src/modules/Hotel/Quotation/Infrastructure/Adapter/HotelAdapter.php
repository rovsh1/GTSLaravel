<?php

namespace Module\Hotel\Quotation\Infrastructure\Adapter;

use Module\Hotel\Moderation\Infrastructure\Models\Room;
use Module\Hotel\Quotation\Domain\Adapter\HotelAdapterInterface;

class HotelAdapter implements HotelAdapterInterface
{
    public function isRoomExists(int $roomId): bool
    {
        return (bool)Room::find($roomId);
    }
}