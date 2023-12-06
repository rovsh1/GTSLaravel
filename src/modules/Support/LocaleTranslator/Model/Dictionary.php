<?php

namespace Module\Support\LocaleTranslator\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dictionary extends Model
{
    use SoftDeletes;

    protected $table = 'r_locale_dictionary';

    protected string $valuesTable = 'r_locale_dictionary_values';

    protected $fillable = [
        'key'
    ];

    public static function findByKey(string $key): ?Dictionary
    {
        return static::where('key', $key)->first();
    }

    public function scopeWhereKey(Builder $query, string $key): void
    {
        $query->where('key', $key);
    }

    public function scopeJoinLocale(Builder $query, string $locale): void
    {
        $query
            ->addSelect('r_locale_dictionary.key')
            ->addSelect('values.value')
            ->join($this->valuesTable . ' as values', 'values.dictionary_id', '=', 'r_locale_dictionary.id')
            ->where('values.language', $locale);
    }
}
