<?php

namespace Support\LocaleTranslator\Storage;

use Support\LocaleTranslator\Model\Dictionary;

class DatabaseStorage
{
    public function load(string $locale): array
    {
        $items = [];
        $q = Dictionary::joinLocale($locale);
        foreach ($q->cursor() as $r) {
            $items[$r->key] = $r->value;
        }

        return $items;
    }
}