<?php

namespace Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Support;

use Carbon\CarbonInterface;
use Module\Booking\HotelBooking\Domain\Adapter\HotelAdapterInterface;
use Module\Shared\Domain\Adapter\CurrencyRateAdapterInterface;
use Module\Shared\Enum\CurrencyEnum;

class NetPriceFetcher
{
    public function __construct(
        private readonly HotelAdapterInterface $hotelAdapter,
        private readonly CurrencyRateAdapterInterface $currencyRateAdapter,
    ) {
    }

    public function fetch(
        int $roomId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        CurrencyEnum $orderCurrency,
        CurrencyEnum $hotelCurrency,
        CarbonInterface $date
    ): float {
        $roomPrice = $this->hotelAdapter->getRoomPrice(
            $roomId,
            $rateId,
            $isResident,
            $guestsCount,
            $date
        ) ?? 0;

        return $this->currencyRateAdapter->convertNetRate($roomPrice, $hotelCurrency, $orderCurrency, 'UZ');
    }
}
