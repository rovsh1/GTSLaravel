<?php

namespace Module\Support\LocaleTranslator\Storage;

//@todo implement redis cache
class CacheStorage
{
    public function get(string $locale): ?array
    {
        return null;
    }

    public function put(string $locale, array $items): void {}
}