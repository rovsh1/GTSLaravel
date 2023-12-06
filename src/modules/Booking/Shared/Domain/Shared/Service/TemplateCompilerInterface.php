<?php

namespace Module\Booking\Shared\Domain\Shared\Service;

interface TemplateCompilerInterface
{
    public function compile(string $template, array $attributes): string;
}
