<?php

namespace Module\Hotel\Quotation\Domain\Adapter;

interface HotelAdapterInterface
{
    public function getRoomHotelId(int $roomId): ?int;

    public function isRoomExists(int $roomId): bool;
}
