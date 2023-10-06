<?php

declare(strict_types=1);

namespace Module\Booking\Application\ServiceBooking\Dto;

use Carbon\CarbonImmutable;
use Module\Booking\Application\HotelBooking\Dto\Details\CancelConditionsDto;
use Module\Booking\Application\Shared\Response\BookingDto as BaseDto;
use Module\Booking\Application\Shared\Response\BookingPriceDto;
use Module\Booking\Application\Shared\Response\StatusDto;

class BookingDto extends BaseDto
{
    public function __construct(
        int $id,
        StatusDto $status,
        int $orderId,
        CarbonImmutable $createdAt,
        int $creatorId,
        public readonly ?string $note,
        public readonly CancelConditionsDto $cancelConditions,
        public readonly BookingPriceDto $price,
    ) {
        parent::__construct($id, $status, $orderId, $createdAt, $creatorId);
    }
}
