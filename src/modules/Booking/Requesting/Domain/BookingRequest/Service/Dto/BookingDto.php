<?php

declare(strict_types=1);

namespace Module\Booking\Requesting\Domain\BookingRequest\Service\Dto;

class BookingDto
{
    public function __construct(
        public readonly int $number,
        public readonly string $status,
        public readonly string $createdAt,
        public readonly string $updatedAt,
        public readonly BookingPriceDto $clientPrice,
        public readonly BookingPriceDto $supplierPrice,
        public readonly ?string $note,
    ) {}
}
