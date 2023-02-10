<?php

namespace GTS\Integration\Traveline\Application\Dto;

use Custom\Framework\Foundation\Support\Dto\Dto;
use Custom\Framework\Foundation\Support\Dto\DtoCollection;
use Custom\Framework\Foundation\Support\Dto\Optional;

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

    public static function collectionFromHotelRooms(mixed $hotelRoomsData): array
    {
        return array_map(fn($hotelRoomData) => static::fromHotelRoom($hotelRoomData), $hotelRoomsData);
    }

    public static function fromHotelRoom(mixed $hotelRoomData): self
    {
        $occupancies = [];
        for ($i = 1; $i <= $hotelRoomData->guestsNumber; $i++) {
            $occupancies[] = [
                'id' => $hotelRoomData->id,
                'personQuantity' => $i
            ];
        }

        return new self(
            $hotelRoomData->id,
            $hotelRoomData->name,
            RatePlanDto::collection($hotelRoomData->priceRates),
            OccupancyDto::collection($occupancies),
        );
    }
}
