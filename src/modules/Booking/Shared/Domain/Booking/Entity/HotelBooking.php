<?php

namespace Module\Booking\Shared\Domain\Booking\Entity;

use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\DetailsId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\BookingPeriod;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\ExternalNumber;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\HotelInfo;
use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;
use Module\Shared\Enum\ServiceTypeEnum;

final class HotelBooking implements ServiceDetailsInterface
{
    public function __construct(
        private readonly DetailsId $id,
        private readonly BookingId $bookingId,
        private readonly HotelInfo $hotelInfo,
        private BookingPeriod $bookingPeriod,
        private ?ExternalNumber $externalNumber,
        private readonly QuotaProcessingMethodEnum $quotaProcessingMethod,
    ) {}

    public function serviceType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::HOTEL_BOOKING;
    }

    public function id(): DetailsId
    {
        return $this->id;
    }

    public function bookingId(): BookingId
    {
        return $this->bookingId;
    }

    public function hotelInfo(): HotelInfo
    {
        return $this->hotelInfo;
    }

    public function bookingPeriod(): BookingPeriod
    {
        return $this->bookingPeriod;
    }

    public function setBookingPeriod(BookingPeriod $period): void
    {
        $this->bookingPeriod = $period;
    }

    public function externalNumber(): ?ExternalNumber
    {
        return $this->externalNumber;
    }

    public function setExternalNumber(ExternalNumber|null $number): void
    {
        $this->externalNumber = $number;
    }

    public function quotaProcessingMethod(): QuotaProcessingMethodEnum
    {
        return $this->quotaProcessingMethod;
    }
}
