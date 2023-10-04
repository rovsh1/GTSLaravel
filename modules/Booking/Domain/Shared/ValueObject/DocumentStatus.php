<?php

namespace Module\Booking\Domain\Shared\ValueObject;

class DocumentStatus
{
    public function __construct(
        private readonly bool $actual,
        private readonly bool $status,
    ) {}
}
