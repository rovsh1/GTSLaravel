<?php

namespace Module\Booking\PriceCalculator\Domain\Service\HotelBooking;

use Module\Booking\Hotel\Domain\ValueObject\Details\BookingPeriod;
use Module\Shared\Enum\CurrencyEnum;

class CalculateVariables
{
    public function __construct(
        public readonly CurrencyEnum $orderCurrency,
        public readonly BookingPeriod $bookingPeriod,
        public readonly int $roomId,
        public readonly int $rateId,
        public readonly bool $isResident,
        public readonly int $guestsCount,
        public readonly int $vatPercent,
        public readonly int $clientMarkupPercent,
        public readonly float $touristTax,
        public readonly ?int $earlyCheckInPercent,
        public readonly ?int $lateCheckOutPercent,
    ) {
    }
}
