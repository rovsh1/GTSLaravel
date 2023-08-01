<?php

namespace Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Support;

use Carbon\CarbonInterface;
use Module\Booking\HotelBooking\Domain\Adapter\HotelAdapterInterface;
use Module\Booking\HotelBooking\Domain\Exception\NotFoundHotelRoomPrice;
use Module\Shared\Domain\Adapter\CurrencyRateAdapterInterface;
use Module\Shared\Enum\CurrencyEnum;

class NetPriceFetcher
{
    public function __construct(
        private readonly HotelAdapterInterface $hotelAdapter,
        private readonly CurrencyRateAdapterInterface $currencyRateAdapter,
    ) {}

    /**
     * @param int $roomId
     * @param int $rateId
     * @param bool $isResident
     * @param int $guestsCount
     * @param CurrencyEnum $orderCurrency
     * @param CurrencyEnum $hotelCurrency
     * @param CarbonInterface $date
     * @return float
     * @throws NotFoundHotelRoomPrice
     */
    public function fetch(
        int $roomId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        CurrencyEnum $orderCurrency,
        CurrencyEnum $hotelCurrency,
        CarbonInterface $date
    ): float {
        if ($guestsCount === 0) {
            //@todo спросить у Сергея точно ли в этом месте
            return 0;
        }
        $roomPrice = $this->hotelAdapter->getRoomPrice(
            $roomId,
            $rateId,
            $isResident,
            $guestsCount,
            $date
        );
        if ($roomPrice === null) {
            throw new NotFoundHotelRoomPrice('Room price not found.');
        }

        return $this->currencyRateAdapter->convertNetRate($roomPrice, $hotelCurrency, $orderCurrency, 'UZ');
    }
}
