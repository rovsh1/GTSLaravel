<?php

declare(strict_types=1);

namespace Module\Shared\Infrastructure\Service;

use Module\Shared\Domain\Service\TranslatorInterface;
use Module\Shared\Infrastructure\Models\TranslationItem;

class Translator implements TranslatorInterface
{
    public function translate(string $key, array $replace = [], ?string $locale = null): string
    {
        $preparedLocale = $locale !== null ? $locale : 'ru';
        $translationKey = $key;
        $translationItem = TranslationItem::whereName($key)->first();
        if ($translationItem !== null) {
            $property = "value_{$preparedLocale}";
            $translationKey = $translationItem->$property;
        }
        if ($translationKey === null) {
            return $key;
        }
        return __($translationKey, $replace, $locale);
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
