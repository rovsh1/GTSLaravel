<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

Builder::macro('joinTranslatable', function ($table, string|array $columns = null, $joinType = 'left') {
    $alias = $table;
    foreach (($this->getQuery()->joins ?? []) as $join) {
        if (is_string($join->table) && str_contains($join->table, ' as ') && str_ends_with($join->table, $table)) {
            [$table, $alias] = explode(' as ', $join->table);
            break;
        }
    }
    $language = App::currentLocale();
    $translatableTable = $table . '_translation';
    $translatableAlias = $alias . '_translation';
    $joinMethod = match ($joinType) {
        'left' => 'leftJoin',
        default => 'join'
    };

    $this->$joinMethod(
        DB::raw('(SELECT t.* FROM ' . $translatableTable . ' as t WHERE t.language="' . $language . '") as ' . $translatableAlias),
        function ($join) use ($alias, $translatableAlias) {
            $join->on($translatableAlias . '.translatable_id', '=', $alias . '.id');
        }
    );

    if ($columns) {
        if (!is_array($columns)) {
            $columns = [$columns];
        }

        $this->addSelect(array_map(fn($c) => $translatableAlias . '.' . $c . '', $columns));
    }

    return $this;
});
