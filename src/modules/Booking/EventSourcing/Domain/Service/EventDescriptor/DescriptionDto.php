<?php

declare(strict_types=1);

namespace Module\Booking\EventSourcing\Domain\Service\EventDescriptor;

use Module\Booking\EventSourcing\Domain\ValueObject\EventGroupEnum;

final class DescriptionDto
{
    public function __construct(
        public readonly EventGroupEnum|null $group,
        public readonly string|null $field,
        public readonly string $description,
        public readonly mixed $before,
        public readonly mixed $after,
    ) {}
}