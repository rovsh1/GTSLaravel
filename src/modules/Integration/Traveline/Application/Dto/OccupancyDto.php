<?php

namespace Module\Integration\Traveline\Application\Dto;

use Module\Integration\Traveline\Domain\Service\HotelRoomCodeGeneratorInterface;
use Module\Integration\Traveline\Domain\ValueObject\HotelRoomCode;

class OccupancyDto extends \Sdk\Module\Foundation\Support\Dto\Dto
{
    private const DEFAULT_BED_TYPE = 'adultBed';

    public function __construct(
        public readonly string $code,
        public readonly int    $personQuantity,
        public readonly string $bedType = self::DEFAULT_BED_TYPE,
    ) {}

    public static function createByGuestsCount(HotelRoomCodeGeneratorInterface $codeGenerator, int $roomId, int $guestsCount)
    {
        $occupancies = [];
        for ($i = 1; $i <= $guestsCount; $i++) {
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
