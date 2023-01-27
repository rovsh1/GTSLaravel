<?php

namespace GTS\Administrator\Infrastructure\Query;

use GTS\Administrator\Infrastructure\Models\Country;
use GTS\Shared\Application\Query\QueryInterface;

class GetCountriesHandler extends AbstractSearchQueryHandler
{
    public function handle(QueryInterface $query)
    {
        return $this->prepareModelQuery(Country::class, $query)
            ->when($query->default, fn($q, $flag) => $q->where('default', $flag))
            ->when($query->flag, fn($q, $flag) => $q->where('flag', $flag))
            ->get();
    }
}
