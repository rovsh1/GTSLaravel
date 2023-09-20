<?php

declare(strict_types=1);

namespace Module\Shared\ValueObject;

use DateTimeImmutable;

final class Timestamps
{
    public function __construct(
        private readonly DateTimeImmutable $createdDate,
        private readonly DateTimeImmutable $updatedDate,
    ) {
    }

    public function createdDate(): DateTimeImmutable
    {
        return $this->createdDate;
    }

    public function updatedDate(): DateTimeImmutable
    {
        return $this->updatedDate;
    }
}