<?php

namespace Module\Booking\Invoicing\Domain\Service;

interface TemplateCompilerInterface
{
    public function compile(string $template, array $attributes): string;
}
