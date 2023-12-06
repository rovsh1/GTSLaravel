<?php

namespace Module\Support\LocaleTranslator;

use Module\Support\LocaleTranslator\Storage\CacheStorage;
use Module\Support\LocaleTranslator\Storage\DatabaseStorage;

class DictionaryRepository
{
    private array $items;

    public function __construct(
        private readonly CacheStorage $cacheStorage,
        private readonly DatabaseStorage $databaseStorage,
    ) {}

    public function get(string $key, string $locale): string
    {
        return $this->load($locale)[$key] ?? $key;
    }

    private function load(string $locale): array
    {
        if (isset($this->items)) {
            return $this->items;
        }

        $items = $this->cacheStorage->get($locale);
        if ($items) {
            return $this->items = $items;
        }

        $items = $this->databaseStorage->load($locale);
        $this->cacheStorage->put($locale, $items);

        return $this->items = $items;
    }
}