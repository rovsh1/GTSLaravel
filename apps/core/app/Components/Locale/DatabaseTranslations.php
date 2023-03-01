<?php

namespace App\Core\Components\Locale;

use Illuminate\Support\Facades\DB;

class DatabaseTranslations
{
    protected $locale;

    protected array $items;

    public function __construct($locale = null)
    {
        if ($locale) {
            $this->setLocale($locale);
        }
    }

    public function setLocale($locale): void
    {
        $this->locale = $locale;
        $this->items = [];
        $items = DB::table('translation_items')
            ->select('name', 'value_' . $locale . ' as value')
            ->get();
        foreach ($items as $item) {
            $this->items[$item->name] = $item->value;
        }
    }

    public function currentLocale()
    {
        return $this->locale;
    }

    public function items(): array
    {
        return $this->items;
    }

    public function translate(string $key, array $args = [])
    {
        //return app('translator')->get($key, $replace, $locale);
        if (!isset($this->items[$key])) {
            return __($key) ?? $key;
        }

        $value = $this->items[$key];
        foreach ($args as $key => $arg) {
            $value = str_replace(':' . $key, $arg, $value);
        }

        return $value;
    }
}
