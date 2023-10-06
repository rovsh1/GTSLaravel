<?php

namespace Module\Pricing\Domain\HotelBooking\Service;

use DateTimeImmutable;
use Module\Hotel\Application\Response\HotelDto;
use Module\Hotel\Application\Response\MarkupSettingsDto;
use Module\Pricing\Domain\HotelBooking\Booking;
use Module\Pricing\Domain\HotelBooking\Entity\RoomBooking;
use Module\Shared\Enum\CurrencyEnum;

class RoomDataHelper
{
    public function __construct(
        public readonly RoomBooking $roomBooking,
        public readonly Booking $hotelBooking,
        public readonly HotelDto $hotelDto,
        public readonly MarkupSettingsDto $markupDto
    ) {
    }

    public function roomId(): int
    {
        return $this->roomBooking->hotelRoomId()->value();
    }

    public function rateId(): int
    {
        return $this->roomBooking->rateId()->value();
    }

    public function isResident(): bool
    {
        return $this->roomBooking->isResident();
    }

    public function guestsCount(): int
    {
        return $this->roomBooking->guestCount();
    }

    public function clientId(): int
    {
        return $this->hotelBooking->clientId()->value();
    }

    public function orderCurrency(): CurrencyEnum
    {
        return $this->hotelBooking->currency();
    }

    public function hotelCurrency(): CurrencyEnum
    {
        return CurrencyEnum::from($this->hotelDto->currency);
    }

    public function startDate(): DateTimeImmutable
    {
        return $this->hotelBooking->period()->start();
    }

    public function endDate(): DateTimeImmutable
    {
        return $this->hotelBooking->period()->end();
    }

    public function earlyCheckInPercent(): ?int
    {
        return $this->roomBooking->earlyCheckIn()?->priceMarkup()->value();
    }

    public function lateCheckOutPercent(): ?int
    {
        return $this->roomBooking->lateCheckOut()?->priceMarkup()->value();
    }

    public function bookingPeriodDates(): array
    {
        return $this->hotelBooking->period()->includedDates();
    }
}
