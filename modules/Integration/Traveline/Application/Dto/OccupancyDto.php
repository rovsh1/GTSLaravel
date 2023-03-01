<?php

namespace Module\Integration\Traveline\Application\Dto;

use Custom\Framework\Foundation\Support\Dto\Dto;
use Module\Integration\Traveline\Domain\Service\HotelRoomCodeGeneratorInterface;
use Module\Integration\Traveline\Domain\ValueObject\HotelRoomCode;

class OccupancyDto extends Dto
{
    private const DEFAULT_BED_TYPE = 'adultBed';

    public function __construct(
        public readonly string $code,
        public readonly int    $personQuantity,
        public readonly string $bedType = self::DEFAULT_BED_TYPE,
    ) {}

    public static function createByGuestsNumber(HotelRoomCodeGeneratorInterface $codeGenerator, int $roomId, int $guestsNumber)
    {
        $occupancies = [];
        for ($i = 1; $i <= $guestsNumber; $i++) {
            /** @var string $roomCode */
            $roomCode = $codeGenerator->stringifyRoomCode(new HotelRoomCode($roomId, $i));
            $occupancies[] = [
                'code' => $roomCode,
                'personQuantity' => $i
            ];
        }
        return static::collection($occupancies);
    }
}
