<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Domain\Invoice\Service\Dto\Booking;

class DailyMarkupDto
{
    public function __construct(
        public readonly string $percent,
        public readonly string $daysCount,
    ) {}
}
