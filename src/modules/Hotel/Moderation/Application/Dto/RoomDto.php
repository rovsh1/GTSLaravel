<?php

namespace Module\Hotel\Moderation\Application\Dto;

final class RoomDto
{
    /**
     * @param int $id
     * @param int $hotelId
     * @param string $name
     * @param PriceRateDto[] $priceRates
     * @param int $guestsCount
     * @param int $roomsCount
     */
    public function __construct(
        public readonly int $id,
        public readonly int $hotelId,
        public readonly string $name,
        public readonly array $priceRates,
        public readonly int $guestsCount,
        public readonly int $roomsCount,
    ) {
    }
}
