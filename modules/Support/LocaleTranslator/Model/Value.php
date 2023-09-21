<?php

namespace Module\Support\LocaleTranslator\Model;

use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    protected $table = 'r_locale_dictionary_values';

    protected $fillable = [
        'translation',
        'locale',
        'key',
        'value'
    ];

    public function scopeWhereLocale($query, string $locale): void
    {
        $query->where('locale', $locale);
    }
}
