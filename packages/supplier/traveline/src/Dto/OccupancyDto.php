<?php

namespace Pkg\Supplier\Traveline\Dto;

use Custom\Framework\Foundation\Support\Dto\Dto;

class OccupancyDto extends Dto
{
    private const DEFAULT_BED_TYPE = 'adultBed';

    public function __construct(
        public readonly string $code,
        public readonly int $personQuantity,
        public readonly string $bedType = self::DEFAULT_BED_TYPE,
    ) {}

    public static function createByGuestsNumber(int $roomId, int $guestsNumber)
    {
        $occupancies = [];
        for ($i = 1; $i <= $guestsNumber; $i++) {
            $occupancies[] = [
                'code' => (string)(new HotelRoomCodeDto($roomId, $i)),
                'personQuantity' => $i
            ];
        }

        return static::collection($occupancies);
    }
}
