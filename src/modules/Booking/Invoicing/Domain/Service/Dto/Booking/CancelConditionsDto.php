<?php

declare(strict_types=1);

namespace Module\Booking\Invoicing\Domain\Service\Dto\Booking;

class CancelConditionsDto
{
    /**
     * @param int $noCheckInMarkup
     * @param string $noCheckInMarkupType
     * @param DailyCancelFeeValueDto[] $dailyMarkups
     * @param \DateTimeInterface $cancelNoFeeDate
     */
    public function __construct(
        public readonly float $noCheckInMarkup,
        public readonly string $noCheckInMarkupType,
        public readonly array $dailyMarkups,
        public readonly \DateTimeInterface $cancelNoFeeDate
    ) {}
}
