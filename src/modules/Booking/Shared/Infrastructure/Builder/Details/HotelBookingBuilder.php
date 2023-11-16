<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Builder\Details;

use Module\Booking\Shared\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\DetailsId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\BookingPeriod as HotelBookingPeriod;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\ExternalNumber;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\HotelInfo;
use Module\Booking\Shared\Infrastructure\Models\Details\Hotel;

class HotelBookingBuilder
{
    public function build(Hotel $details): HotelBooking
    {
        $detailsData = $details->data;

        $externalNumberData = $detailsData['externalNumber'] ?? null;

        return new HotelBooking(
            id: new DetailsId($details->id),
            bookingId: new BookingId($details->booking_id),
            hotelInfo: HotelInfo::fromData($detailsData['hotelInfo']),
            bookingPeriod: HotelBookingPeriod::fromData($detailsData['period']),
            externalNumber: $externalNumberData ? ExternalNumber::fromData($externalNumberData) : null,
            quotaProcessingMethod: $details->quota_processing_method,
        );
    }
}
