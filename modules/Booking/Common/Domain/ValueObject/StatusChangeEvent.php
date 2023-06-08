<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\ValueObject;

use Carbon\CarbonImmutable;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class StatusChangeEvent implements ValueObjectInterface
{
    public function __construct(
        private readonly string $event,
        private readonly int $source,
        private readonly int $userId,
        private readonly CarbonImmutable $dateCreate
    ) {}

    public function event(): string
    {
        return $this->event;
    }

    public function source(): int
    {
        return $this->source;
    }

    public function userId(): int
    {
        return $this->userId;
    }

    public function dateCreate(): CarbonImmutable
    {
        return $this->dateCreate;
    }
}
