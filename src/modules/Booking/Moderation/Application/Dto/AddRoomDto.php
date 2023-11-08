<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto;

class AddRoomDto
{
    public function __construct(
        public readonly int $bookingId,
        public readonly int $roomId,
        public readonly int $rateId,
        public readonly bool $isResident,
        public readonly array|null $earlyCheckIn = null,
        public readonly array|null $lateCheckOut = null,
        public readonly ?string $note = null,
        public readonly ?int $discount = null
    ) {}
}
