<?php

declare(strict_types=1);

namespace Module\Booking\EventSourcing\Application\Dto;

use DateTimeInterface;

final class EventDto
{
    public function __construct(
        public readonly string|null $event,
        public readonly string $description,
        public readonly ?string $color,
        public readonly array $payload,
        public readonly array $context,
        public readonly DateTimeInterface $createdAt
    ) {}
}
