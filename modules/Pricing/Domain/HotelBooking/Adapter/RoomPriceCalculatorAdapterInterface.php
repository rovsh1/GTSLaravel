<?php

declare(strict_types=1);

namespace Module\Pricing\Domain\HotelBooking\Adapter;

use DateTimeInterface;
use Module\Shared\Enum\CurrencyEnum;

interface RoomPriceCalculatorAdapterInterface
{
    public function calculateSupplierPrice(
        int $clientId,
        int $roomId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        CurrencyEnum $outCurrency,
        DateTimeInterface $date
    );

    public function calculateClientPrice(
        int $clientId,
        int $roomId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        CurrencyEnum $outCurrency,
        DateTimeInterface $date
    );
}
