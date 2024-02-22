<?php

declare(strict_types=1);

namespace Sdk\Shared\Contracts\Service;

interface TranslatorInterface
{
    public function locale(): string;

    public function setLocale(string $locale): void;

    public function translate(string $key, array $replace = [], ?string $locale = null): string;

    public function translateEnum(\UnitEnum $enum, ?string $locale = null): string;
}
