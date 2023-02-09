<?php

namespace GTS\Administrator\Infrastructure\Facade\Reference;

use Custom\Framework\Contracts\Bus\QueryBusInterface;

use GTS\Administrator\Domain\Repository\CurrencyRepositoryInterface;

class CurrencyFacade implements CurrencyFacadeInterface
{
    public function __construct(
        private readonly CurrencyRepositoryInterface $currencyRepository
    ) {}

    public function findById()
    {
        return $this->currencyRepository->find();
    }

    public function search(mixed $params = null)
    {
        // map to dto collection
        return $this->currencyRepository->search($params);
    }

    public function count(mixed $params = null): int
    {
        return $this->currencyRepository->count($params);
    }
}
