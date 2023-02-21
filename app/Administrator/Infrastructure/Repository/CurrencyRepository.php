<?php

namespace GTS\Administrator\Infrastructure\Repository;

use App\Admin\Models\Currency;
use GTS\Administrator\Domain\Repository\CurrencyRepositoryInterface;
use GTS\Administrator\Infrastructure\Query\CurrencySearchQuery;
use Module\Shared\Infrastructure\Repository\AbstractCrudRepository;

class CurrencyRepository extends AbstractCrudRepository implements CurrencyRepositoryInterface
{
    protected string $model = Currency::class;

    public function __construct(
        //public readonly QueryBusInterface $queryBus
    ) {}

    public function search(mixed $paramsDto)
    {
        return (new CurrencySearchQuery($paramsDto))->get();
    }

    public function count(mixed $paramsDto): int
    {
        return (new CurrencySearchQuery($paramsDto))->count();
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
