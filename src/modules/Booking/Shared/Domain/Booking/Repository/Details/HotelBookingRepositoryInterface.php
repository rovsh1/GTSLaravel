<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Repository\Details;

use Module\Booking\Shared\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\BookingPeriod;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\HotelInfo;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\AccommodationIdCollection;
use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;

interface HotelBookingRepositoryInterface
{
    public function find(BookingId $bookingId): ?HotelBooking;

    public function findOrFail(BookingId $bookingId): HotelBooking;

    public function create(
        BookingId $bookingId,
        HotelInfo $hotelInfo,
        BookingPeriod $bookingPeriod,
        AccommodationIdCollection $accommodations,
        QuotaProcessingMethodEnum $quotaProcessingMethod,
    ): HotelBooking;

    public function store(HotelBooking $details): bool;
}
