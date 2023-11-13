<?php

declare(strict_types=1);

namespace Module\Booking\Invoicing\Domain\Service\Dto\Booking;

class DailyMarkupDto
{
    public function __construct(
        public readonly string $percent,
        public readonly string $daysCount,
    ) {}
}
