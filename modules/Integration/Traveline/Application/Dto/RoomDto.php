<?php

namespace Module\Integration\Traveline\Application\Dto;

use Custom\Framework\Foundation\Support\Dto\Dto;
use Custom\Framework\Foundation\Support\Dto\DtoCollection;
use Custom\Framework\Foundation\Support\Dto\Optional;
use Module\Integration\Traveline\Domain\Service\HotelRoomCodeGeneratorInterface;

class RoomDto extends Dto
{
    public function __construct(
        public readonly int                    $roomTypeId,
        public readonly string                 $roomName,
        /** @var RatePlanDto[] $ratePlans */
        public readonly DtoCollection          $ratePlans,
        /** @var OccupancyDto[] $occupancies */
        public readonly DtoCollection|Optional $occupancies
    ) {}

    public static function collectionFromHotelRooms(HotelRoomCodeGeneratorInterface $codeGenerator, mixed $hotelRoomsData): array
    {
        return array_map(fn($hotelRoomData) => static::fromHotelRoom($codeGenerator, $hotelRoomData), $hotelRoomsData);
    }

    public static function fromHotelRoom(HotelRoomCodeGeneratorInterface $codeGenerator, mixed $hotelRoomData): self
    {
        return new self(
            $hotelRoomData->id,
            $hotelRoomData->name,
            RatePlanDto::collection($hotelRoomData->priceRates),
            OccupancyDto::createByGuestsNumber($codeGenerator, $hotelRoomData->id, $hotelRoomData->guestsNumber),
        );
    }
}
