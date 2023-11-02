<?php

declare(strict_types=1);

namespace Module\Booking\Application\Dto\ServiceBooking;

use Module\Booking\Application\Dto\Details\BookingPeriodDto;
use Module\Booking\Application\Dto\Details\ExternalNumberDto;
use Module\Booking\Application\Dto\Details\HotelInfoDto;
use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;

class HotelBookingDto implements ServiceDetailsDtoInterface
{
    public function __construct(
        public readonly int $id,
        public readonly HotelInfoDto $hotelInfo,
        public readonly BookingPeriodDto $period,
        public readonly array $roomBookings,
        public readonly ?ExternalNumberDto $externalNumber,
        public readonly QuotaProcessingMethodEnum $quotaProcessingMethod,
    ) {}
}
