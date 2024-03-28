<?php

namespace Pkg\Supplier\Traveline\Dto;

use Pkg\Supplier\Traveline\Exception\InvalidHotelRoomCode;

final class HotelRoomCodeDto implements \Stringable
{
    public function __construct(
        public readonly int $roomId,
        public readonly int $personQuantity,
    ) {}

    private const ROOM_CODE_SEPARATOR = '_';

    public static function fromString(string $code): self
    {
        try {
            [$roomId, $guestsCount] = explode(self::ROOM_CODE_SEPARATOR, $code);
        } catch (\Throwable $e) {
            throw new InvalidHotelRoomCode();
        }
        if (!is_numeric($roomId) || !is_numeric($guestsCount)) {
            throw new InvalidHotelRoomCode();
        }

        return new self($roomId, $guestsCount);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->roomId . self::ROOM_CODE_SEPARATOR . $this->personQuantity;
    }
}
