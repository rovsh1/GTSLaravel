<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Factory\Details;

use Module\Booking\Shared\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\BookingPeriod;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\HotelInfo;
use Sdk\Shared\Enum\Booking\QuotaProcessingMethodEnum;

interface HotelBookingFactoryInterface
{
    public function create(
        BookingId $bookingId,
        HotelInfo $hotelInfo,
        BookingPeriod $bookingPeriod,
        QuotaProcessingMethodEnum $quotaProcessingMethod,
    ): HotelBooking;
}
