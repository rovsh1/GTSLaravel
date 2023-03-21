<?php

namespace Module\Integration\Traveline\Domain\Service;

use Module\Integration\Traveline\Domain\Exception\InvalidHotelRoomCode;
use Module\Integration\Traveline\Domain\ValueObject\HotelRoomCode;

class HotelRoomCodeGenerator implements HotelRoomCodeGeneratorInterface
{
    private const ROOM_CODE_SEPARATOR = '_';

    public function stringifyRoomCode(HotelRoomCode $roomCode): string
    {
        return $roomCode->roomId . self::ROOM_CODE_SEPARATOR . $roomCode->personQuantity;
    }

    public function parseRoomCode(string $code): HotelRoomCode
    {
        try {
            [$roomId, $guestsNumber] = explode(self::ROOM_CODE_SEPARATOR, $code);
        } catch (\Throwable $e) {
            throw new InvalidHotelRoomCode();
        }
        if (!is_numeric($roomId) || !is_numeric($guestsNumber)) {
            throw new InvalidHotelRoomCode();
        }
        return new HotelRoomCode($roomId, $guestsNumber);
    }
}
