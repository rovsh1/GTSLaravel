<?php

namespace Module\Hotel\Quotation\Domain\Adapter;

interface HotelAdapterInterface
{
    public function isRoomExists(int $roomId): bool;
}