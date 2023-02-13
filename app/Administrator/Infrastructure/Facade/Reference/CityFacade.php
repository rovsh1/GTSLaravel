<?php

namespace GTS\Administrator\Infrastructure\Facade\Reference;

use Custom\Framework\Contracts\Bus\QueryBusInterface;

use GTS\Administrator\Domain\Repository\CityRepositoryInterface;

class CityFacade implements CityFacadeInterface
{
    public function __construct(
        private readonly CityRepositoryInterface $cityRepository
    ) {}

    public function findById()
    {
        return $this->cityRepository->find();
    }

    public function search(mixed $params = null)
    {
        // map to dto collection
        return $this->cityRepository->search($params);
    }

    public function count(mixed $params = null): int
    {
        return $this->cityRepository->count($params);
    }
}
