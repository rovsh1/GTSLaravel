<?php

namespace Custom\Framework\Database\Query;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class TranslationQueryBuilder
{
    private ?string $locale = null;

    public function __construct(private readonly Builder $builder) {}

    public function locale(?string $locale): static
    {
        $this->locale = $locale;
        return $this;
    }

    public function join(string $model, string|array $columns = null, string $joinType = 'inner'): static
    {
        $language = $this->locale ?? App::currentLocale();

        $currentTable = $this->builder->getModel()->getTable();
        //$modelTable = with(new $model)->getTable();
        $translatableTable = with(new $model)->getTranslationTable();

        $joinMethod = match ($joinType) {
            'left' => 'leftJoin',
            default => 'join'
        };

        $this->builder->$joinMethod(
            DB::raw('(SELECT t.* FROM ' . $translatableTable . ' as t WHERE t.language="' . $language . '") as ' . $translatableTable),
            function ($join) use ($currentTable, $translatableTable) {
                $join->on($translatableTable . '.translatable_id', '=', $currentTable . '.id');
            }
        );

        if ($columns) {
            if (!is_array($columns))
                $columns = [$columns];

            $this->builder->addSelect(array_map(fn($c) => $translatableTable . '.' . $c . '', $columns));
        }

        return $this;
    }

    public function leftJoin(string $model, string|array $columns = null): static
    {
        return $this->join($model, $columns, 'left');
    }
}
