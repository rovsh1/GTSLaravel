<?php

namespace Module\Booking\Shared\Infrastructure\Service;

use Module\Booking\Shared\Domain\Shared\Service\TemplateCompilerInterface;

class DocxTemplateCompiler implements TemplateCompilerInterface
{
    public function compile(string $template, array $attributes): string
    {
        // TODO: Implement compile() method.
    }
}
