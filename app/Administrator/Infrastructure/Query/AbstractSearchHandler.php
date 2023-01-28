<?php

namespace GTS\Administrator\Infrastructure\Query;

use GTS\Shared\Application\Query\QueryHandlerInterface;
use GTS\Shared\Application\Query\QueryInterface;

abstract class AbstractSearchHandler implements QueryHandlerInterface
{

    protected function prepareModelQuery(string $modelClass, QueryInterface $query)
    {
        return $modelClass::query()
            ->when($query->orderBy(), fn($q, $name) => $q->orderBy($name, $query->sortOrder()))
            ->when($query->limit(), fn($q, $limit) => $q->limit($limit))
            ->offset($query->offset());
    }

}
