<?php

namespace Sdk\Module\Database\Eloquent;

use Illuminate\Database\Eloquent\Builder;

trait HasQuicksearch
{

//    protected $quicksearch = ['id'];

    public function fieldIdentifier($name)
    {
//        static $seoColumns = ['title', 'text', 'head'];

        $clearName = trim($name, '%');
        if ($this->fieldHasTable($clearName)) {
            return $clearName;
        }

        if ($name === 'id') {
            return $this->table . '.' . $clearName;
        } elseif (isset($this->translatable) && in_array($name, $this->translatable)) {
            return $this->getTranslationTable() . '.' . $clearName;
        }
//        elseif (in_array($name, $seoColumns) && method_exists($this, 'seo'))
//            return 'seo_content.' . $name;
        elseif (in_array($name, $this->fillable)) {
            return $this->table . '.' . $clearName;
        } else {
            return $clearName;
        }
    }

    public static function scopeQuicksearch($query, $term)
    {
        if (empty($term)) {
            return;
        }

        $model = $query->getModel();

        $query->where(function ($query) use ($term, $model) {
            foreach ($model->quicksearch as $arg) {
                $a = explode('.', $arg);
                $column = $a[1] ?? $a[0];
                $t = $term;
                if (str_starts_with($column, '%')) {
                    $t = '%' . $t;
                }
                if (str_ends_with($column, '%')) {
                    $t = $t . '%';
                }

                if (isset($a[1])) {
                    $identifier = $a[0] . '.' . trim($column, '%');
                } else {
                    $identifier = $model->fieldIdentifier($column);
                }

                $query->orWhere($identifier, 'like', $t);
            }
        });
    }

    public function scopeApplyCriteria(Builder $query, array $criteria): void
    {
        if (isset($criteria['quicksearch'])) {
            $query->quicksearch($criteria['quicksearch']);
            unset($criteria['quicksearch']);
        }

        foreach ($criteria as $k => $v) {
            $scopeName = \Str::camel($k);
            $scopeMethod = 'where' . ucfirst($scopeName);
            $hasScope = $this->hasNamedScope($scopeMethod);
            if ($hasScope) {
                $query->$scopeMethod($v);
                continue;
            }
            $query->where($k, $v);
        }
    }

    private function fieldHasTable(string $field): bool
    {
        return mb_strpos($field, '.') !== false;
    }

}
