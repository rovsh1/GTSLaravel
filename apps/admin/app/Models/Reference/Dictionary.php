<?php

namespace App\Admin\Models\Reference;

use App\Admin\Support\Facades\Languages;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Dictionary extends \Support\LocaleTranslator\Model\Dictionary
{
    public function storeValues(array $values): void
    {
        foreach (Languages::all() as $language) {
            if (empty($values[$language->code])) {
                DB::table($this->valuesTable)
                    ->where('dictionary_id', $this->id)
                    ->where('language', $language->code)
                    ->delete();
            } else {
                DB::table($this->valuesTable)
                    ->updateOrInsert([
                        'dictionary_id' => $this->id,
                        'language' => $language->code
                    ], [
                        'value' => $values[$language->code]
                    ]);
            }
        }
    }

    public function scopeWithValues(Builder $query): void
    {
        $query->addSelect('r_locale_dictionary.key');
        foreach (Languages::all() as $language) {
            $query->addSelect(
                DB::raw(
                    "(SELECT value FROM $this->valuesTable
                 WHERE dictionary_id=r_locale_dictionary.id
                 AND language='$language->code') as value_$language->code"
                )
            );
        }
    }

    public function scopeWhereTerm(Builder $query, string $term): void
    {
        $term = $term . '%';
        $query
            ->where('key', 'like', $term)
            ->orWhereExists(function ($query) use ($term) {
                $query->select(DB::raw(1))
                    ->from($this->valuesTable . ' as t')
                    ->whereColumn('t.dictionary_id', 'r_locale_dictionary.id')
                    ->where('t.value', 'like', $term);
            });
    }

    public function scopeWhereHasEmptyValue(Builder $query): void
    {
        foreach (Languages::all() as $language) {
            $query->orWhereNotExists(function ($query) use ($language) {
                $query->select(DB::raw(1))
                    ->from($this->valuesTable . ' as t')
                    ->whereColumn('t.dictionary_id', 'r_locale_dictionary.id')
                    ->where('t.language', $language->code);
            });
        }
    }
}
