<?php

declare(strict_types=1);

namespace Support\LocaleTranslator;

use Sdk\Shared\Contracts\Service\TranslatorInterface;
use UnitEnum;

class Translator implements TranslatorInterface
{
    private array $enumNamespaces = [
        'Sdk\\Shared\\Enum\\' => '',
        'Sdk\\Booking\\Enum\\' => 'Booking\\'
    ];

    private string $locale;

    public function __construct(private readonly DictionaryRepository $dictionaryRepository)
    {
        $this->locale = app()->getLocale();
    }

    public function locale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    public function translate(string $key, array $replace = [], ?string $locale = null): string
    {
        $locale ??= $this->locale ?? app()->getLocale();
        $line = $this->dictionaryRepository->get($key, $locale);

        return $this->makeReplacements($line, $replace);
    }

    public function translateEnum(UnitEnum $enum, ?string $locale = null): string
    {
        $key = $this->buildEnumKey($enum);

        return $this->translate($key, [], $locale);
    }

    private function buildEnumKey(UnitEnum $enum): string
    {
        $key = $enum::class;
        foreach ($this->enumNamespaces as $k => $v) {
            $key = str_replace($k, $v, $key);
        }

        return 'Enum::' . $key . '::' . $enum->name;
    }

    private function makeReplacements(string $line, array $replace): string
    {
        if (empty($replace)) {
            return $line;
        }

        $shouldReplace = [];

        foreach ($replace as $key => $value) {
//            if (is_object($value) && isset($this->stringableHandlers[get_class($value)])) {
//                $value = call_user_func($this->stringableHandlers[get_class($value)], $value);
//            }

            $shouldReplace[':' . ucfirst($key ?? '')] = ucfirst($value ?? '');
            $shouldReplace[':' . strtoupper($key ?? '')] = strtoupper($value ?? '');
            $shouldReplace[':' . $key] = $value;
        }

        return strtr($line, $shouldReplace);
    }
}
