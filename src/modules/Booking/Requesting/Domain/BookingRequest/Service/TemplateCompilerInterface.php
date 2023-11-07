<?php

namespace Module\Booking\Requesting\Domain\BookingRequest\Service;

interface TemplateCompilerInterface
{
    public function compile(string $template, array $attributes): string;
}
