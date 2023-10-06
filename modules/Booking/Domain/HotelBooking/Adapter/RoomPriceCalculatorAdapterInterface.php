<?php

declare(strict_types=1);

namespace Module\Booking\Domain\HotelBooking\Adapter;

use DateTimeInterface;
use Module\Shared\Enum\CurrencyEnum;

interface RoomPriceCalculatorAdapterInterface
{
    public function calculateGross(
        int $clientId,
        int $roomId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        CurrencyEnum $outCurrency,
        DateTimeInterface $date
    );

    public function calculateNet(
        int $clientId,
        int $roomId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        CurrencyEnum $outCurrency,
        DateTimeInterface $date
    );
}