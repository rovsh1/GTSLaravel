<?php

namespace GTS\Shared\Custom\Database\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

trait HasTranslantableName
{
    protected $translationLocale;

    public static function bootHasTranslantableName()
    {
        static::addGlobalScope('translatableName', function (Builder $builder) {
            $modelTable = with(new static)->getTable();
            $builder->addSelect($modelTable . '.*');

            static::scopeJoinTranslantableName($builder, ['name']);
        });

        static::saving(function ($model) {
            $model->saveTranslantableName();
        });
    }

    public static function scopeJoinTranslantableName($query, $columns = null, $language = null)
    {
        if (null === $language) {
            $language = App::currentLocale();
        }

        $translatableTable = with(new static())->getTranslationTable();
        $modelTable = with(new static)->getTable();

        $query->leftJoin(
            DB::raw('(SELECT t.* FROM ' . $translatableTable . ' as t WHERE t.language="' . $language . '") as ' . $translatableTable),
            function ($join) use ($translatableTable, $modelTable) {
                $join->on($translatableTable . '.translatable_id', '=', $modelTable . '.name_id');
            }
        );

        if ($columns) {
            if (!is_array($columns))
                $columns = [$columns];

            $query->addSelect(
                array_map(function ($c) use ($translatableTable) {
                    return $translatableTable . '.' . $c . '';
                }, $columns)
            );
        }
    }

    public function getTranslationTable(): string
    {
        return 'r_enums_translation';
    }

    public function getLocale()
    {
        return $this->translationLocale ?? $this->language ?? App::currentLocale();
    }

    public function setLocale(string $locale): self
    {
        $this->translationLocale = $locale;

        return $this;
    }

    public function saveTranslantableName(array $options = []): void
    {
        $translationData = ['name' => $this->name, 'language' => $this->getLocale(), 'translatable_id' => $this->name_id];
        DB::table($this->getTranslationTable())->upsert($translationData, 'translatable_id', ['name', 'language']);
        $this->offsetUnset('name');
    }
}
