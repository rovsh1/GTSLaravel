<?php

namespace Module\Integration\Traveline\Application\Dto;

use Sdk\Module\Foundation\Support\Dto\Dto;

class HotelDto extends Dto
{
    public function __construct(
        public readonly int   $hotelId,
        /** @var RoomDto[] $roomsAndRatePlans */
        public readonly array $roomsAndRatePlans,
    ) {}

    /**
     * @param mixed $hotelData
     * @param RoomDto[] $roomsAndRatePlans
     * @return static
     */
    public static function fromHotel(mixed $hotelData, array $roomsAndRatePlans): self
    {
        return new self(
            $hotelData->id,
            $roomsAndRatePlans
        );
    }
}
