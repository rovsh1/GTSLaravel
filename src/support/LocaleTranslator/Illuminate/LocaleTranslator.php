<?php

namespace Support\LocaleTranslator\Illuminate;

use Illuminate\Contracts\Translation\Loader;
use Illuminate\Translation\Translator;
use Sdk\Shared\Contracts\Service\TranslatorInterface;
use UnitEnum;

class LocaleTranslator extends Translator
{
    public function __construct(
        private readonly TranslatorInterface $appTranslator,
        Loader $loader,
        $locale
    ) {
        parent::__construct($loader, $locale);
    }

    public function setLocale($locale)
    {
        parent::setLocale($locale);

        $this->appTranslator->setLocale($locale);
    }

    public function get($key, array $replace = [], $locale = null, $fallback = true)
    {
        if ($key instanceof UnitEnum) {
            return $this->appTranslator->translateEnum($key, $locale);
        }

        $line = parent::get($key, $replace, $locale, $fallback);
        if (!empty($line) && $line !== $key) {
            return $line;
        }

        return $this->appTranslator->translate($key, $replace, $locale);
    }

    public function translateEnum(UnitEnum $enum, ?string $locale = null): string
    {
        return $this->appTranslator->translateEnum($enum, $locale);
    }
}