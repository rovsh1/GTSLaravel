<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Dto;

use Module\Booking\Application\Admin\HotelBooking\Dto\Details\AdditionalInfo\ExternalNumberDto;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\BookingPeriodDto;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\HotelInfoDto;
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