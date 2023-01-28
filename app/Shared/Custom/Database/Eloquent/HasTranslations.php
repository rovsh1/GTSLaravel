<?php

namespace GTS\Shared\Custom\Database\Eloquent;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Builder;

trait HasTranslations
{

    protected $translationLocale;

    protected $translationData;

    protected $translationAttributes;

    public static function bootHasTranslations()
    {
        static::addGlobalScope('translatable', function (Builder $builder) {
            $modelTable = with(new static)->getTable();
            $columns = with(new static)->translatable;
            $builder->addSelect($modelTable . '.*');

            static::scopeJoinTranslations($builder, $columns);
        });

        static::saved(function ($model) {
            $model->saveTranslations();
        });
    }

    public static function scopeJoinTranslations($query, $columns = null, $language = null)
    {
        if (null === $language)
            $language = App::currentLocale();

        $translatableTable = with(new static)->getTranslationTable();
        $modelTable = with(new static)->getTable();

        $query->leftJoin(
            DB::raw('(SELECT t.* FROM ' . $translatableTable . ' as t WHERE t.language="' . $language . '") as ' . $translatableTable),
            function ($join) use ($translatableTable, $modelTable) {
                $join->on($translatableTable . '.translatable_id', '=', $modelTable . '.id');
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
        return $this->table . '_translation';
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

    public function isTranslatable($column): bool
    {
        return in_array($column, $this->translatable);
    }

    public function hasTranslation($language, $column = null): bool
    {
        $query = DB::table($this->getTranslationTable())
            ->where('translatable_id', $this->id)
            ->where('language', $language);
        if ($column)
            $query->whereNotNull($column);

        return (bool)$query->exists();
    }

    public function getTranslationData(): array
    {
        if (null !== $this->translationData)
            return $this->translationData;

        $this->translationData = [];

        $data = DB::table($this->getTranslationTable())
            ->where('translatable_id', $this->id)
            ->get();

        foreach ($data as $r) {
            unset($r->translatable_id);
            $this->translationData[$r->language] = $r;
        }

        return $this->translationData;
    }

    public function getTranslation($key, $locale = null)
    {
        if (null === $locale)
            $locale = $this->getLocale();

        if (isset($this->translationAttributes[$key][$locale]))
            return $this->translationAttributes[$key][$locale];
        elseif (null !== $this->translationData)
            return $this->translationData[$locale]->$key ?? null;

        $this->getTranslationData();

        return $this->translationData[$locale]->$key ?? null;
    }

    public function setTranslation($key, $value, $locale = null): static
    {
        if (!isset($this->translationAttributes[$key]))
            $this->translationAttributes[$key] = [];

        $this->translationAttributes[$key][$locale ?? $this->getLocale()] = $value;

        return $this;
    }

    public function getTranslations($key)
    {
        if (isset($this->translationAttributes[$key]))
            return $this->translationAttributes[$key];

        $values = [];
        foreach ($this->getTranslationData() as $language => $r) {
            $values[$language] = $r->$key;
        }

        return $values;
    }

    public function setTranslations($key, array $values): static
    {
        $this->translationAttributes[$key] = [];

        foreach ($values as $locale => $value) {
            $this->translationAttributes[$key][$locale] = $value;
        }

        return $this;
    }

    public function removeTranslations()
    {
        $q = DB::table($this->getTranslationTable())
            ->where('translatable_id', $this->id);

        $q->delete();
    }

    public function setTranslatableAttribute($key, $value): void
    {
        if (is_array($value))
            $this->translationAttributes[$key] = $value;
        else {
            if (!isset($this->translationAttributes[$key]))
                $this->translationAttributes[$key] = [];
            $this->translationAttributes[$key][$this->getLocale()] = $value;
        }
    }

    public function saveTranslations(array $options = []): void
    {
        if (empty($this->translationAttributes))
            return;

        $langData = [];
        foreach ($this->translationAttributes as $key => $data) {
            foreach ($data as $lang => $v) {
                if (!isset($langData[$lang]))
                    $langData[$lang] = [];
                $langData[$lang][$key] = $v;
            }
        }

        foreach ($langData as $language => $data) {
            if ($this->hasTranslation($language))
                DB::table($this->getTranslationTable())
                    ->where('translatable_id', $this->id)
                    ->where('language', $language)
                    ->update($data);
            else {
                Db::table($this->getTranslationTable())
                    ->insert(
                        array_merge($data, [
                            'translatable_id' => $this->id,
                            'language' => $language
                        ])
                    );
            }
        }

        $this->translationAttributes = [];
    }

}
