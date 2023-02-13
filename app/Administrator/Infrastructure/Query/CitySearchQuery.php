<?php

namespace GTS\Administrator\Infrastructure\Query;

use GTS\Administrator\Infrastructure\Models\City;
use GTS\Shared\Infrastructure\Query\SearchQuery;

class CitySearchQuery extends SearchQuery
{
    public function __construct(mixed $paramsDto)
    {
        parent::__construct(City::class, $paramsDto);
    }

    protected function beforeSelect(): void
    {
        $this->joinTranslations();
    }

    protected function filter(): void
    {
        // $this->when('default', fn($q, $flag) => $q->where('default', $flag))
        //    ->when('flag', fn($q, $flag) => $q->where('flag', $flag));
    }
}
