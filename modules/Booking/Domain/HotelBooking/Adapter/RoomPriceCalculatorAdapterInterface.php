<?php

declare(strict_types=1);

namespace Module\Booking\Domain\HotelBooking\Adapter;

use Carbon\CarbonInterface;
use Module\Shared\Enum\CurrencyEnum;

interface RoomPriceCalculatorAdapterInterface
{
    public function calculate(
        int $clientId,
        int $roomId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        CurrencyEnum $outCurrency,
        CarbonInterface $date,
        ?float $grossDayPrice,
        ?float $netDayPrice,
    );
}
