<?php

declare(strict_types=1);

namespace Module\Booking\Transfer\Application\Response;

use Carbon\CarbonImmutable;
use Module\Booking\Common\Application\Response\BookingDto as BaseDto;
use Module\Booking\Common\Application\Response\BookingPriceDto;
use Module\Booking\Common\Application\Response\StatusDto;
use Module\Booking\HotelBooking\Application\Dto\Details\CancelConditionsDto;

class BookingDto extends BaseDto
{
    public function __construct(
        int $id,
        StatusDto $status,
        int $orderId,
        CarbonImmutable $createdAt,
        int $creatorId,
        public readonly ?string $note,
        public readonly ServiceInfoDto $serviceInfo,
        public readonly CancelConditionsDto $cancelConditions,
        public readonly BookingPriceDto $price,
    ) {
        parent::__construct($id, $status, $orderId, $createdAt, $creatorId);
    }
}
