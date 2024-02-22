<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Shared\Service;

interface MailTemplateCompilerInterface
{
    public function compile(string $template, array $attributes): string;
}
