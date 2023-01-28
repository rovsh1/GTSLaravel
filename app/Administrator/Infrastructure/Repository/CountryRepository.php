<?php

namespace GTS\Administrator\Infrastructure\Repository;

use GTS\Administrator\Domain\Repository\CountryRepositoryInterface;
use GTS\Administrator\Infrastructure\Models\Country;
use GTS\Administrator\Infrastructure\Query\CountrySearchQuery;
use GTS\Shared\Application\Query\QueryBusInterface;
use GTS\Shared\Infrastructure\Repository\AbstractCrudRepository;

class CountryRepository extends AbstractCrudRepository implements CountryRepositoryInterface
{

    protected string $model = Country::class;

    public function __construct(private readonly QueryBusInterface $queryBus) {}

    public function search(mixed $paramsDto)
    {
        return (new CountrySearchQuery($paramsDto))->get();
        //return $this->queryBus->execute(Search::fromDto($paramsDto));
    }

    public function count(mixed $paramsDto): int
    {
        return (new CountrySearchQuery($paramsDto))->count();
        //return $this->queryBus->execute(Count::fromDto($paramsDto));
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
