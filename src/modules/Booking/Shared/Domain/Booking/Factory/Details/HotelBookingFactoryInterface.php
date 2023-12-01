<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Factory\Details;

use Sdk\Booking\Entity\Details\HotelBooking;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\HotelBooking\BookingPeriod;
use Sdk\Booking\ValueObject\HotelBooking\HotelInfo;
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
