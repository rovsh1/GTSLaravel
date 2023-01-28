<?php

namespace GTS\Administrator\Infrastructure\Facade\Reference;

use GTS\Administrator\Domain\Repository\CountryRepositoryInterface;

class CountryFacade implements CountryFacadeInterface
{
    public function __construct(private readonly CountryRepositoryInterface $repository) {}

    public function search(mixed $params)
    {
        $entities = $this->repository->search($params);
        // map to dto collection
        return $entities;
    }
}
