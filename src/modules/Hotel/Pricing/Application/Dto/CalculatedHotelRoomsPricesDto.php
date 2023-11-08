<?php

declare(strict_types=1);

namespace Module\Hotel\Pricing\Application\Dto;

use Module\Shared\Enum\CurrencyEnum;

class CalculatedHotelRoomsPricesDto
{
    /**
     * @param RoomCalculationResultDto[] $rooms
     */
    public function __construct(
        public readonly float $price,
        public readonly CurrencyEnum $currency,
        public readonly array $rooms
    ) {
    }
}
