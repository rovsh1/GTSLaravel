<?php

namespace Module\Booking\Moderation\Domain\Booking\Service\ChangeHistory;

class ChangedAttribute
{
    public function __construct(
        public readonly string $name,
        public readonly string $type,
        public readonly mixed $valueBefore,
        public readonly mixed $valueAfter,
    ) {
    }
}