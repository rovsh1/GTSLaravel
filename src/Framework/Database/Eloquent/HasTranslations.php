<?php

namespace Custom\Framework\Database\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

trait HasTranslations
{
    protected $translationLocale;

    protected $translationData;

    protected $translationAttributes;

    public static function bootHasTranslations()
    {
        //static::addGlobalTranslationScope();
        static::saved(function ($model) { $model->saveTranslations(); });
    }

    protected static function addGlobalTranslationScope(): void
    {
        static::addGlobalScope('translatable', function (Builder $builder) {
            $modelTable = with(new static)->getTable();
            $columns = with(new static)->translatable;
            $builder->addSelect($modelTable . '.*');
            $builder->joinTranslations($columns);
        });
    }

    public function scopeJoinTranslations($builder, $columns = null): void
    {
        $modelTable = with(new static)->getTable();
        if (null === $columns) {
            $columns = $builder->getModel()->translatable;
        }
        $builder->joinTranslatable($modelTable, $columns, 'left');
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
        if ($column) {
            $query->whereNotNull($column);
        }

        return (bool)$query->exists();
    }

    public function getTranslationData(): array
    {
        if (null !== $this->translationData) {
            return $this->translationData;
        }

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
        if (null === $locale) {
            $locale = $this->getLocale();
        }

        if (isset($this->translationAttributes[$key][$locale])) {
            return $this->translationAttributes[$key][$locale];
        } elseif (null !== $this->translationData) {
            return $this->translationData[$locale]->$key ?? null;
        }

        $this->getTranslationData();

        return $this->translationData[$locale]->$key ?? null;
    }

    public function setTranslation($key, $value, $locale = null): static
    {
        if (!isset($this->translationAttributes[$key])) {
            $this->translationAttributes[$key] = [];
        }

        $this->translationAttributes[$key][$locale ?? $this->getLocale()] = $value;

        return $this;
    }

    public function getTranslations($key)
    {
        if (isset($this->translationAttributes[$key])) {
            return $this->translationAttributes[$key];
        }

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
        if (is_array($value)) {
            $this->translationAttributes[$key] = $value;
        } else {
            if (!isset($this->translationAttributes[$key])) {
                $this->translationAttributes[$key] = [];
            }
            $this->translationAttributes[$key][$this->getLocale()] = $value;
        }
    }

    public function saveTranslations(array $options = []): void
    {
        if (empty($this->translationAttributes)) {
            return;
        }

        $langData = [];
        foreach ($this->translationAttributes as $key => $data) {
            foreach ($data as $lang => $v) {
                if (!isset($langData[$lang])) {
                    $langData[$lang] = [];
                }
                $langData[$lang][$key] = $v;
            }
        }

        foreach ($langData as $language => $data) {
            if ($this->hasTranslation($language)) {
                DB::table($this->getTranslationTable())
                    ->where('translatable_id', $this->id)
                    ->where('language', $language)
                    ->update($data);
            } elseif (array_filter($data)) {
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
