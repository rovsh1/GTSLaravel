<?php

declare(strict_types=1);

namespace Pkg\Booking\EventSourcing\Domain\Service\EventDescriptor;

use Pkg\Booking\EventSourcing\Domain\ValueObject\EventGroupEnum;

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