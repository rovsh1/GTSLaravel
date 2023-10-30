<?php

declare(strict_types=1);

namespace Module\Pricing\Application\RequestDto;

use Carbon\CarbonPeriod;
use Module\Pricing\Application\Dto\RoomCalculationParamsDto;
use Module\Shared\Enum\CurrencyEnum;

class CalculateHotelPriceRequestDto
{
    /**
     * @param int $hotelId
     * @param RoomCalculationParamsDto[] $rooms
     * @param CurrencyEnum $outCurrency
     * @param CarbonPeriod $period
     * @param int|null $clientId
     */
    public function __construct(
        public readonly int $hotelId,
        public readonly array $rooms,
        public readonly CurrencyEnum $outCurrency,
        public readonly CarbonPeriod $period,
        public readonly ?int $clientId = null,
    ) {
    }
}
