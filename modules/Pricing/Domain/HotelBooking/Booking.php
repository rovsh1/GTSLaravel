<?php

declare(strict_types=1);

namespace Module\Pricing\Domain\HotelBooking;

use Module\Pricing\Domain\Hotel\ValueObject\HotelId;
use Module\Pricing\Domain\HotelBooking\Event\BookingPriceChanged;
use Module\Pricing\Domain\HotelBooking\ValueObject\BookingId;
use Module\Pricing\Domain\HotelBooking\ValueObject\BookingPrice;
use Module\Pricing\Domain\Shared\ValueObject\ClientId;
use Module\Shared\Domain\ValueObject\DatePeriod;
use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

abstract class Booking extends AbstractAggregateRoot
{
    public function __construct(
        private readonly BookingId $id,
        private readonly ClientId $clientId,
        private readonly HotelId $hotelId,
        private readonly CurrencyEnum $currency,
        private readonly DatePeriod $period,
        private BookingPrice $price,
    ) {
    }

    public function id(): BookingId
    {
        return $this->id;
    }

    public function clientId(): ClientId
    {
        return $this->clientId;
    }

    public function hotelId(): HotelId
    {
        return $this->hotelId;
    }

    public function currency(): CurrencyEnum
    {
        return $this->currency;
    }

    public function period(): DatePeriod
    {
        return $this->period;
    }

    public function price(): BookingPrice
    {
        return $this->price;
    }

    public function updatePrice(BookingPrice $price): void
    {
        if (!$price->isEqual($this->price)) {
            return;
        }

        $priceBefore = $this->price;
        $this->price = $price;
        $this->pushEvent(
            new BookingPriceChanged($this, $priceBefore)
        );
    }
}
