<?php

declare(strict_types=1);

namespace Module\Booking\Requesting\Domain\Service\EventComparator;

final class ChangesDto
{
    public function __construct(
        public readonly string $field,
        public readonly mixed $before,
        public readonly mixed $after
    ) {}
}