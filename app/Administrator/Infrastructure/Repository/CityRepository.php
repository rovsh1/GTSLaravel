<?php

namespace GTS\Administrator\Infrastructure\Repository;

use Custom\Framework\Contracts\Bus\QueryBusInterface;

use GTS\Shared\Infrastructure\Repository\AbstractCrudRepository;
use GTS\Administrator\Domain\Repository\CityRepositoryInterface;
use GTS\Administrator\Infrastructure\Models\Currency;
use GTS\Administrator\Infrastructure\Query\CitySearchQuery;

class CityRepository extends AbstractCrudRepository implements CityRepositoryInterface
{
    protected string $model = Currency::class;

    public function __construct(
        public readonly QueryBusInterface $queryBus
    ) {}

    public function search(mixed $paramsDto)
    {
        return (new CitySearchQuery($paramsDto))->get();
    }

    public function count(mixed $paramsDto): int
    {
        return (new CitySearchQuery($paramsDto))->count();
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
