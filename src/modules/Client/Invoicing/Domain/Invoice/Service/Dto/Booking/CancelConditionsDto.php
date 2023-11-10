<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Domain\Invoice\Service\Dto\Booking;

class CancelConditionsDto
{
    /**
     * @param string $noCheckInMarkup
     * @param DailyMarkupDto[] $dailyMarkups
     */
    public function __construct(
        public readonly string $noCheckInMarkup,
        public readonly array $dailyMarkups
    ) {}
}
