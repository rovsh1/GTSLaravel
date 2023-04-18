<?php

namespace Module\Booking\Common\Domain\ValueObject;

class DocumentStatus
{
    public function __construct(
        private readonly bool $actual,
        private readonly bool $status,
    ) {}
}
