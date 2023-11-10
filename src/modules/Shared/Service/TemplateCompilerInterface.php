<?php

namespace Module\Shared\Service;

interface TemplateCompilerInterface
{
    public function compile(string $template, array $attributes): string;
}
