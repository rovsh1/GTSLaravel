<?php

namespace Supplier\Traveline\Domain\Service;

use Supplier\Traveline\Domain\ValueObject\HotelRoomCode;

interface HotelRoomCodeGeneratorInterface
{
    public function stringifyRoomCode(HotelRoomCode $roomCode): string;

    public function parseRoomCode(string $code): HotelRoomCode;
}
