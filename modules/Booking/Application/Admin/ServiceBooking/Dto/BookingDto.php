<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Dto;

use Carbon\CarbonImmutable;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\CancelConditionsDto;
use Module\Booking\Application\Admin\Shared\Response\BookingDto as BaseDto;
use Module\Booking\Application\Admin\Shared\Response\BookingPriceDto;
use Module\Booking\Application\Admin\Shared\Response\StatusDto;

class BookingDto extends BaseDto
{
    public function __construct(
        int $id,
        StatusDto $status,
        int $orderId,
        CarbonImmutable $createdAt,
        int $creatorId,
        BookingPriceDto $price,
        CancelConditionsDto $cancelConditions,
        ?string $note,
        public readonly ServiceTypeDto $serviceType,
        public readonly ?ServiceDetailsDtoInterface $details,
    ) {
        parent::__construct($id, $status, $orderId, $createdAt, $creatorId, $price, $cancelConditions, $note);
    }
}
