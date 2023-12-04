<?php

namespace Supplier\Traveline\Application\Dto;

use Sdk\Module\Foundation\Support\Dto\DtoCollection;
use Supplier\Traveline\Domain\Service\HotelRoomCodeGeneratorInterface;

class RoomDto
{
    public function __construct(
        public readonly int $roomTypeId,
        public readonly string $roomName,
        /** @var RatePlanDto[] $ratePlans */
        public readonly DtoCollection $ratePlans,
        /** @var OccupancyDto[] $occupancies */
        public readonly DtoCollection|\Sdk\Module\Foundation\Support\Dto\Optional $occupancies
    ) {}

    public static function collectionFromHotelRooms(
        HotelRoomCodeGeneratorInterface $codeGenerator,
        mixed $hotelRoomsData
    ): array {
        return array_map(fn($hotelRoomData) => static::fromHotelRoom($codeGenerator, $hotelRoomData), $hotelRoomsData);
    }

    public static function fromHotelRoom(HotelRoomCodeGeneratorInterface $codeGenerator, mixed $hotelRoomData): self
    {
        return new self(
            $hotelRoomData->id,
            $hotelRoomData->name,
            RatePlanDto::collection($hotelRoomData->priceRates),
            OccupancyDto::createByGuestsCount($codeGenerator, $hotelRoomData->id, $hotelRoomData->guestsCount),
        );
    }
}
