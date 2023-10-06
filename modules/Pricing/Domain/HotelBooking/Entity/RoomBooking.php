<?php

declare(strict_types=1);

namespace Module\Pricing\Domain\HotelBooking\Entity;

use Module\Pricing\Domain\Hotel\ValueObject\RoomId;
use Module\Pricing\Domain\HotelBooking\Service\RoomPriceBuilder;
use Module\Pricing\Domain\HotelBooking\ValueObject\BookingId;
use Module\Pricing\Domain\HotelBooking\ValueObject\RateId;
use Module\Pricing\Domain\HotelBooking\ValueObject\RoomBookingId;
use Module\Pricing\Domain\HotelBooking\ValueObject\RoomPrice;
use Module\Shared\Domain\Entity\EntityInterface;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

class RoomBooking extends AbstractAggregateRoot implements EntityInterface
{
    public function __construct(
        private readonly RoomBookingId $id,
        private readonly BookingId $bookingId,
        private readonly RoomId $hotelRoomId,
        private readonly RateId $rateId,
        private readonly bool $isResident,
        private readonly int $guestCount,
        private RoomPrice $price,
    ) {
    }

    public function id(): RoomBookingId
    {
        return $this->id;
    }

    public function bookingId(): BookingId
    {
        return $this->bookingId;
    }

    public function hotelRoomId(): RoomId
    {
        return $this->hotelRoomId;
    }

    public function rateId(): RateId
    {
        return $this->rateId;
    }

    public function isResident(): bool
    {
        return $this->isResident;
    }

    public function guestCount(): int
    {
        return $this->guestCount;
    }

    public function price(): RoomPrice
    {
        return $this->price;
    }

    public function updatePrice(RoomPrice $price): void
    {
        $this->price = $price;
    }

    public function recalculatePrices(RoomPriceBuilder $builder): void
    {
        $this->price = $builder
            ->forBooking($this)
            ->build();
    }

    public function setNetDayPrice(float $price, RoomPriceBuilder $builder): void
    {
        $this->price = $builder
            ->forBooking($this)
            ->withManualNetValue($price)
            ->build();
    }

    public function setGrossDayPrice(float $price, RoomPriceBuilder $builder): void
    {
        $this->price = $builder
            ->forBooking($this)
            ->withManualGrossValue($price)
            ->build();
    }

    public function setCalculatedPrices(RoomPriceBuilder $builder): void
    {
        $this->price = $builder
            ->forBooking($this)
            ->withManualGrossValue(null)
            ->withManualNetValue(null)
            ->build();
    }

    public function setCalculatedGrossPrice(RoomPriceBuilder $builder): void
    {
        $this->price = $builder
            ->forBooking($this)
            ->withManualGrossValue(null)
            ->build();
    }

    public function setCalculatedNetPrice(RoomPriceBuilder $builder): void
    {
        $this->price = $builder
            ->forBooking($this)
            ->withManualNetValue(null)
            ->build();
    }
}
