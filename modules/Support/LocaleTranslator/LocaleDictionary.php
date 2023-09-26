<?php

namespace Module\Support\LocaleTranslator;

use Module\Support\LocaleTranslator\Model\Dictionary;

class LocaleDictionary
{
    private array $items = [];

    public function translate(string $key, array $replace = [], string $locale = null): string
    {
        if (!isset($this->items)) {
            $this->items = $this->load($locale ?? app()->getLocale());
        }

        return $this->items[$key] ?? $key;
    }

    private function load(string $locale): array
    {
        $items = [];
        $q = Dictionary::joinLocale($locale);
        foreach ($q->cursor() as $r) {
            $items[$r->key] = $r->value;
        }

        return $items;
    }
}