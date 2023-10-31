<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Dto;

use Carbon\CarbonImmutable;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\CancelConditionsDto;
use Module\Booking\Application\Admin\Shared\Response\BookingPriceDto;
use Module\Booking\Application\Admin\Shared\Response\StatusDto;

class BookingDto
{
    public function __construct(
        public readonly int $id,
        public readonly StatusDto $status,
        public readonly int $orderId,
        public readonly CarbonImmutable $createdAt,
        public readonly int $creatorId,
        public readonly BookingPriceDto $prices,
        public readonly CancelConditionsDto $cancelConditions,
        public readonly ?string $note,
        public readonly ServiceTypeDto $serviceType,
        public readonly ?ServiceDetailsDtoInterface $details,
    ) {
    }
}
