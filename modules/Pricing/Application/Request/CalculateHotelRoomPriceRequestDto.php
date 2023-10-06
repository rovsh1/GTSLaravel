<?php

declare(strict_types=1);

namespace Module\Pricing\Application\Request;

use Carbon\CarbonInterface;
use Module\Shared\Enum\CurrencyEnum;

class CalculateHotelRoomPriceRequestDto
{
    public function __construct(
        public readonly int $clientId,
        public readonly int $roomId,
        public readonly int $rateId,
        public readonly bool $isResident,
        public readonly int $guestsCount,
        public readonly CurrencyEnum $outCurrency,
        public readonly CarbonInterface $date,
        public readonly bool $withMarkups = false,
    ) {
    }
}
