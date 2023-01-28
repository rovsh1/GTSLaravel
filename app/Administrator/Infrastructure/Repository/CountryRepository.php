<?php

namespace GTS\Administrator\Infrastructure\Repository;

use GTS\Administrator\Application\Query\Country\Search;
use GTS\Administrator\Domain\Repository\CountryRepositoryInterface;
use GTS\Administrator\Infrastructure\Models\Country;
use GTS\Shared\Application\Query\QueryBusInterface;

class CountryRepository extends AbstractCrudRepository implements CountryRepositoryInterface
{

    protected string $model = Country::class;

    public function __construct(private readonly QueryBusInterface $queryBus) {}

    public function search(mixed $paramsDto)
    {
        return $this->queryBus->execute(Search::fromDto($paramsDto));
    }

    protected function createEntityFromModel($model)
    {
        // Convert to domain entity
        return $model;
    }

    protected function mapDtoToData(mixed $dto): array
    {
        return (array)$dto;
    }
}
