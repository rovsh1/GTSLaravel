<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\RequestDto;

class UpdateRoomRequestDto
{
    public function __construct(
        public readonly int $bookingId,
        public readonly int $roomBookingId,
        public readonly int $roomId,
        public readonly int $rateId,
        public readonly bool $isResident,
        public readonly array|null $earlyCheckIn = null,
        public readonly array|null $lateCheckOut = null,
        public readonly ?string $note = null,
        public readonly ?int $discount = null
    ) {}
}
