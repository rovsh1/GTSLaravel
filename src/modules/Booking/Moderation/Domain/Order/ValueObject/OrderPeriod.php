<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Order\ValueObject;

class OrderPeriod
{
    public function __construct(
        private readonly \DateTimeImmutable $dateFrom,
        private readonly \DateTimeImmutable $dateTo,
    ) {}

    public function dateFrom(): \DateTimeImmutable
    {
        return $this->dateFrom;
    }

    public function dateTo(): \DateTimeImmutable
    {
        return $this->dateTo;
    }
}
