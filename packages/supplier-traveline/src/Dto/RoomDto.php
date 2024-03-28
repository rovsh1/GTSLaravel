<?php

namespace Pkg\Supplier\Traveline\Dto;

use Module\Hotel\Moderation\Application\Dto\RoomDto as HotelRoomDto;

class RoomDto
{
    public function __construct(
        public readonly int $roomTypeId,
        public readonly string $roomName,
        /** @var RatePlanDto[] $ratePlans */
        public readonly array $ratePlans,
        /** @var OccupancyDto[] $occupancies */
        public readonly array $occupancies
    ) {}

    /**
     * @param HotelRoomDto[] $hotelRoomsData
     * @return array
     */
    public static function collectionFromHotelRooms(array $hotelRoomsData): array
    {
        return array_map(fn($hotelRoomData) => static::fromHotelRoom($hotelRoomData), $hotelRoomsData);
    }

    public static function fromHotelRoom(HotelRoomDto $hotelRoomData): self
    {
        return new self(
            $hotelRoomData->id,
            $hotelRoomData->name,
            RatePlanDto::collection($hotelRoomData->priceRates),
            OccupancyDto::createByGuestsNumber($hotelRoomData->id, $hotelRoomData->guestsCount),
        );
    }
}
