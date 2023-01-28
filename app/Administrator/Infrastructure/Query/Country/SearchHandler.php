<?php

namespace GTS\Administrator\Infrastructure\Query\Country;

use GTS\Administrator\Infrastructure\Models\Country;
use GTS\Administrator\Infrastructure\Query\AbstractSearchHandler;
use GTS\Shared\Application\Query\QueryInterface;

class SearchHandler extends AbstractSearchHandler
{
    public function handle(QueryInterface $query)
    {
        return $this->prepareModelQuery(Country::class, $query)
            ->when(null === $query->default ? null : (int)$query->default, fn($q, $flag) => $q->where('default', $flag))
            ->when($query->flag, fn($q, $flag) => $q->where('flag', $flag))
            ->get()
            // map to domain entities
            //->map()
            ;
    }
}
