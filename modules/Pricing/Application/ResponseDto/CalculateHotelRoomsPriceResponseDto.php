<?php

declare(strict_types=1);

namespace Module\Pricing\Application\ResponseDto;

use Module\Pricing\Application\Dto\RoomCalculationResultDto;
use Module\Shared\Enum\CurrencyEnum;

class CalculateHotelRoomsPriceResponseDto
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
