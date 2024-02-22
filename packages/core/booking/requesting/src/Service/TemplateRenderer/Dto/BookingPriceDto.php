<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Service\TemplateRenderer\Dto;

class BookingPriceDto
{
    public function __construct(
        public readonly float $amount,
        public readonly string $currency,
    ) {}
}
