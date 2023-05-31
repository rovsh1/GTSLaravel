<?php

declare(strict_types=1);

namespace Module\Shared\Domain\Service;

interface TranslatorInterface
{
    public function translate(string $key, array $replace = [], ?string $locale = null): string;

    public function translateEnum(\UnitEnum $enum, ?string $locale = null): string;
}
