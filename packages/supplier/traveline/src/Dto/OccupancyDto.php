<?php

namespace Pkg\Supplier\Traveline\Dto;

final class OccupancyDto
{
    private const DEFAULT_BED_TYPE = 'adultBed';

    public function __construct(
        public readonly string $code,
        public readonly int $personQuantity,
        public readonly string $bedType = self::DEFAULT_BED_TYPE,
    ) {}

    public static function createByGuestsNumber(int $roomId, int $guestsNumber): array
    {
        $occupancies = [];
        for ($i = 1; $i <= $guestsNumber; $i++) {
            $occupancies[] = new self((string)(new HotelRoomCodeDto($roomId, $i)), $i,);
        }

        return $occupancies;
    }
}
