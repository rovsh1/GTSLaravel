<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\Repository\Details;

use Module\Booking\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\BookingPeriod;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\HotelInfo;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingIdCollection;
use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;

interface HotelBookingRepositoryInterface
{
    public function find(BookingId $bookingId): ?HotelBooking;

    public function findOrFail(BookingId $bookingId): HotelBooking;

    public function create(
        BookingId $bookingId,
        HotelInfo $hotelInfo,
        BookingPeriod $bookingPeriod,
        RoomBookingIdCollection $roomBookings,
        QuotaProcessingMethodEnum $quotaProcessingMethod,
    ): HotelBooking;

    public function store(HotelBooking $details): bool;
}
