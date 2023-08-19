<?php

namespace Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Support;

use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\Entity\RoomBooking;
use Module\Booking\Order\Domain\Entity\Order;
use Module\Booking\Order\Domain\ValueObject\ClientId;
use Module\Hotel\Application\Response\HotelDto;
use Module\Hotel\Application\Response\MarkupSettingsDto;
use Module\Hotel\Application\Response\RoomMarkupsDto;
use Module\Shared\Enum\CurrencyEnum;

class RoomDataHelper
{
    public function __construct(
        public readonly RoomBooking $roomBooking,
        public readonly Booking $hotelBooking,
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
        return $this->roomBooking->guests()->count();
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
