<?php

namespace Module\Booking\Domain\Booking\Entity;

use Exception;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\BookingPeriod;
use Module\Booking\Domain\Booking\ValueObject\DetailsId;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\ExternalNumber;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\HotelInfo;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingId;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingIdCollection;
use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;
use Module\Shared\Enum\ServiceTypeEnum;

final class HotelBooking implements ServiceDetailsInterface
{
    public function __construct(
        private readonly DetailsId $id,
        private readonly BookingId $bookingId,
        private readonly HotelInfo $hotelInfo,
        private BookingPeriod $bookingPeriod,
        private RoomBookingIdCollection $roomBookings,
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

    public function roomBookings(): RoomBookingIdCollection
    {
        return $this->roomBookings;
    }

    public function addRoomBooking(RoomBookingId $id): void
    {
        if ($this->roomBookings->has($id)) {
            throw new Exception('Guest already exists');
        }
        $this->roomBookings = new RoomBookingIdCollection([...$this->roomBookings->all(), $id]);
    }

    public function removeRoomBooking(RoomBookingId $roomBookingId): void
    {
        if (!$this->roomBookings->has($roomBookingId)) {
            throw new Exception('RoomBooking not found');
        }
        $this->roomBookings = new RoomBookingIdCollection(
            array_filter($this->roomBookings->all(), fn($id) => !$roomBookingId->isEqual($id))
        );
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
