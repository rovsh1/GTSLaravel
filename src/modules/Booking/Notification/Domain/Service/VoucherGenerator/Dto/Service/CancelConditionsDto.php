<?php

declare(strict_types=1);

namespace Module\Booking\Notification\Domain\Service\VoucherGenerator\Dto\Service;

use Sdk\Module\Support\DateTimeImmutable;

class CancelConditionsDto
{
    /**
     * @param int $noCheckInMarkup
     * @param string $noCheckInMarkupType
     * @param DailyCancelFeeValueDto[] $dailyMarkups
     * @param DateTimeImmutable $cancelNoFeeDate
     */
    public function __construct(
        public readonly float $noCheckInMarkup,
        public readonly string $noCheckInMarkupType,
        public readonly array $dailyMarkups,
        public readonly ?DateTimeImmutable $cancelNoFeeDate
    ) {}
}
