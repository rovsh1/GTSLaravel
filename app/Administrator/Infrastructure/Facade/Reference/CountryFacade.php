<?php

namespace GTS\Administrator\Infrastructure\Facade\Reference;

use GTS\Shared\Application\Query\QueryBusInterface;

use GTS\Administrator\Application\Query\GetCountries;

class CountryFacade implements CountryFacadeInterface
{
    public function __construct(private QueryBusInterface $queryBus) { }

    public function search($params)
    {
        return $this->queryBus->execute(GetCountries::fromDto($params));
    }
}
