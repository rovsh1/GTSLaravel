<?php

namespace Module\Pricing\Domain\HotelPricing\Service\PriceCalculator\Model;

use DateTimeImmutable;
use Module\Booking\Domain\HotelBooking\Entity\RoomBooking;
use Module\Booking\Domain\HotelBooking\HotelBooking;
use Module\Booking\Domain\Order\Order;
use Module\Booking\Domain\Order\ValueObject\ClientId;
use Module\Hotel\Application\Response\HotelDto;
use Module\Hotel\Application\Response\MarkupSettingsDto;
use Module\Hotel\Application\Response\RoomMarkupsDto;
use Module\Shared\Enum\CurrencyEnum;

class RoomDataHelper
{
    public function __construct(
        public readonly RoomBooking $roomBooking,
        public readonly HotelBooking $hotelBooking,
        public readonly Order $order,
        public readonly HotelDto $hotelDto,
        public readonly MarkupSettingsDto $markupDto,
        public readonly ?RoomMarkupsDto $roomMarkupDto,
    ) {
    }

    public function roomId(): int
    {
        return $this->roomBooking->roomInfo()->id();
    }

    public function rateId(): int
    {
        return $this->roomBooking->details()->rateId();
    }

    public function isResident(): bool
    {
        return $this->roomBooking->details()->isResident();
    }

    public function guestsCount(): int
    {
        return $this->roomBooking->guestIds()->count();
    }

    public function clientId(): ClientId
    {
        return $this->order->clientId();
    }

    public function orderCurrency(): CurrencyEnum
    {
        return $this->order->currency();
    }

    public function hotelCurrency(): CurrencyEnum
    {
        return CurrencyEnum::from($this->hotelDto->currency);
    }

    public function startDate(): DateTimeImmutable
    {
        return $this->hotelBooking->period()->dateFrom();
    }

    public function endDate(): DateTimeImmutable
    {
        return $this->hotelBooking->period()->dateTo();
    }

    public function earlyCheckInPercent(): ?int
    {
        return $this->roomBooking->details()->earlyCheckIn()?->priceMarkup()->value();
    }

    public function lateCheckOutPercent(): ?int
    {
        return $this->roomBooking->details()->lateCheckOut()?->priceMarkup()->value();
    }

    public function bookingPeriodDates(): array
    {
        return $this->hotelBooking->period()->includedDates();
    }
}
