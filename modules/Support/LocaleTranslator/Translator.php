<?php

declare(strict_types=1);

namespace Module\Support\LocaleTranslator;

use Module\Shared\Domain\Service\TranslatorInterface;

class Translator implements TranslatorInterface
{
    public function __construct(private readonly LocaleDictionary $localeDictionary)
    {
    }

    public function translate(string $key, array $replace = [], ?string $locale = null): string
    {
        $locale = $locale !== null ? $locale : app()->getLocale();
        $value = __($key, $replace, $locale);
        if ($value !== $key) {
            return $value;
        }

        return $this->localeDictionary->translate($key, $replace, $locale);
    }

    public function translateEnum(\UnitEnum $enum, ?string $locale = null): string
    {
        $key = $this->buildEnumKey($enum);

        return $this->translate($key, [], $locale);
    }

    private function buildEnumKey(\UnitEnum $enum): string
    {
        return $enum::class . '::' . $enum->name;
    }
}
