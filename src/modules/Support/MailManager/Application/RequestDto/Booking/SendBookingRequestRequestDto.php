<?php

declare(strict_types=1);

namespace Module\Support\MailManager\Application\RequestDto\Booking;

final class SendBookingRequestRequestDto
{
    public function __construct(
        public readonly int $bookingId,
        public readonly int $requestId,
        public readonly array $context = []
    ) {
    }
}