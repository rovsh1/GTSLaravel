<?php

namespace Module\Integration\Traveline\Application\Dto;

use Custom\Framework\Foundation\Support\Dto\Dto;

class OccupancyDto extends Dto
{
    private const DEFAULT_BED_TYPE = 'adultBed';

    public function __construct(
        public readonly string $code,
        public readonly int    $personQuantity,
        public readonly string $bedType = self::DEFAULT_BED_TYPE,
    ) {}

    public static function createByGuestsNumber(int $roomId, int $guestsNumber)
    {
        $occupancies = [];
        for ($i = 1; $i <= $guestsNumber; $i++) {
            $occupancies[] = [
                //@todo подумать как формировать этот код и разбирать в одном и том же месте Domain/Api/Request/Price.php:15
                'code' => "{$roomId}_{$i}",
                'personQuantity' => $i
            ];
        }
        return static::collection($occupancies);
    }
}
