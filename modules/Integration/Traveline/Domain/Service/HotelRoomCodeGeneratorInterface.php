<?php

namespace Module\Integration\Traveline\Domain\Service;

use Module\Integration\Traveline\Domain\ValueObject\HotelRoomCode;

interface HotelRoomCodeGeneratorInterface
{
    public function stringifyRoomCode(HotelRoomCode $roomCode): string;

    public function parseRoomCode(string $code): HotelRoomCode;
}
