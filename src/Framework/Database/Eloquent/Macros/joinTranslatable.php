<?php

namespace Custom\Framework\Database\Eloquent\Macros;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

Builder::macro('joinTranslatable', function ($table, string|array $columns = null, $joinType = 'left') {
    $language = App::currentLocale();
    $translatableTable = $table . '_translation';
    $joinMethod = match ($joinType) {
        'left' => 'leftJoin',
        default => 'join'
    };

    $this->$joinMethod(
        DB::raw('(SELECT t.* FROM ' . $translatableTable . ' as t WHERE t.language="' . $language . '") as ' . $translatableTable),
        function ($join) use ($table, $translatableTable) {
            $join->on($translatableTable . '.translatable_id', '=', $table . '.id');
        }
    );

    if ($columns) {
        if (!is_array($columns)) {
            $columns = [$columns];
        }

        $this->addSelect(array_map(fn($c) => $translatableTable . '.' . $c . '', $columns));
    }

    return $this;
});
