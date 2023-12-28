<?php

declare(strict_types=1);

namespace Sdk\Booking\Dto;

use DateTimeImmutable;

final class PeriodDto
{
    public function __construct(
        public readonly DateTimeImmutable $dateFrom,
        public readonly DateTimeImmutable $dateTo,
    ) {}

    public function toArray(): array
    {
        return [
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
        ];
    }
}