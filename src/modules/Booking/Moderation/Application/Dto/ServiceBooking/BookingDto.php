<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto\ServiceBooking;

use DateTimeInterface;
use Module\Booking\Moderation\Application\Dto\BookingPriceDto;
use Module\Booking\Moderation\Application\Dto\BookingStatusDto;
use Module\Booking\Moderation\Application\Dto\Details\CancelConditionsDto;
use Sdk\Booking\Dto\StatusDto;
use Sdk\Shared\Enum\SourceEnum;

class BookingDto
{
    public function __construct(
        public readonly int $id,
        public readonly BookingStatusDto $status,
        public readonly int $orderId,
        public readonly DateTimeInterface $createdAt,
        public readonly int $creatorId,
        public readonly BookingPriceDto $prices,
        public readonly ?CancelConditionsDto $cancelConditions,
        public readonly ?string $note,
        public readonly ServiceTypeDto $serviceType,
        public readonly ?ServiceDetailsDtoInterface $details,
        public readonly SourceEnum $source,
    ) {
    }
}
