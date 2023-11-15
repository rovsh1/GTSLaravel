<?php

declare(strict_types=1);

namespace Module\Booking\Invoicing\Domain\Service\Dto\Booking;

class RoomDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $rate,
        public readonly string $checkInTime,
        public readonly string $checkOutTime,
        /** @var GuestDto[] */
        public readonly array $guests,
        public readonly string|null $note,
        public readonly string $price,
    ) {}
}
