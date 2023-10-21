<?php

namespace Module\Booking\Domain\BookingRequest\Service;

interface TemplateCompilerInterface
{
    public function compile(string $template, array $attributes): string;
}
