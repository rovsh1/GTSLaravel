<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\ResponseDto;

use DateTimeInterface;
use Module\Booking\Moderation\Application\Dto\BookingPriceDto;
use Module\Booking\Moderation\Application\Dto\Details\CancelConditionsDto;
use Module\Booking\Moderation\Application\Dto\ServiceBooking\BookingDto;
use Module\Booking\Moderation\Application\Dto\ServiceBooking\ServiceTypeDto;
use Module\Booking\Shared\Application\Dto\StatusDto;
use Module\Shared\Enum\SourceEnum;

class OrderBookingDto extends BookingDto
{
    public function __construct(
        int $id,
        StatusDto $status,
        int $orderId,
        DateTimeInterface $createdAt,
        int $creatorId,
        BookingPriceDto $prices,
        ?CancelConditionsDto $cancelConditions,
        ?string $note,
        ServiceTypeDto $serviceType,
        SourceEnum $source,
        public readonly ?OrderBookingPeriodDto $bookingPeriod,
        public readonly OrderBookingServiceInfoDto $serviceInfo
    ) {
        parent::__construct(
            $id,
            $status,
            $orderId,
            $createdAt,
            $creatorId,
            $prices,
            $cancelConditions,
            $note,
            $serviceType,
            null,
            $source
        );
    }
}