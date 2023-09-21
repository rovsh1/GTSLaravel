<?php

namespace Module\Support\LocaleTranslator\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Dictionary extends Model
{
    protected $table = 'r_locale_dictionary';

    private string $valuesTable = 'r_locale_dictionary_values';

    protected $fillable = [
        'key',
        'description'
    ];

    public function scopeJoinLocale(Builder $query, string $locale): void
    {
        $query
            ->addSelect('r_locale_dictionary.key')
            ->addSelect('values.value')
            ->leftJoin($this->valuesTable . ' as values', 'values.dictionary_id', '=', 'r_locale_dictionary.id')
            ->where('values.locale', $locale);
    }
}
