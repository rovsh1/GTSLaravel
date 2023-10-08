<?php

declare(strict_types=1);

namespace Module\Booking\Application\TransferBooking\Response;

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
        public readonly ?string $note,
        public readonly ServiceInfoDto $serviceInfo,
        public readonly CancelConditionsDto $cancelConditions,
        public readonly BookingPriceDto $price,
    ) {
        parent::__construct($id, $status, $orderId, $createdAt, $creatorId);
    }
}
