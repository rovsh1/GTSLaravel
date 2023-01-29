<?php

namespace GTS\Administrator\Infrastructure\Facade\Reference;

use GTS\Administrator\Domain\Repository\CountryRepositoryInterface;

class CountryFacade implements CountryFacadeInterface
{
    public function __construct(public readonly CountryRepositoryInterface $repository) {}

    public function search(mixed $params = null)
    {
        $entities = $this->repository->search($params);
        // map to dto collection
        return $entities;
    }

    public function count(mixed $params = null): int
    {
        return $this->repository->count($params);
    }
}
