<?php

namespace GTS\Administrator\Infrastructure\Facade\Reference;

use GTS\Administrator\Domain\Repository\CountryRepositoryInterface;

class CountryFacade implements CountryFacadeInterface
{
    public function __construct(
        public readonly CountryRepositoryInterface $repository
    ) {}

    public function search(mixed $params = null)
    {
        // map to dto collection
        return $this->repository->search($params);
    }

    public function count(mixed $params = null): int
    {
        return $this->repository->count($params);
    }
}
