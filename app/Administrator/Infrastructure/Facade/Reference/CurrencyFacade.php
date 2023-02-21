<?php

namespace GTS\Administrator\Infrastructure\Facade\Reference;

use Custom\Framework\Contracts\Bus\CommandBusInterface;

use GTS\Administrator\Application\Command\Reference\{StoreCurrency, UpdateCurrency};
use GTS\Administrator\Domain\Repository\CurrencyRepositoryInterface;

class CurrencyFacade implements CurrencyFacadeInterface
{
    public function __construct(
        private readonly CurrencyRepositoryInterface $currencyRepository,
        private readonly CommandBusInterface $commandBus
    ) {}

    public function findById(int $id)
    {
        return $this->currencyRepository->find($id);
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

    public function store(array $params): ?int
    {
        return $this->commandBus->execute(new StoreCurrency($params));
    }

    public function update(int $id, array $params): ?int
    {
        return $this->commandBus->execute(new UpdateCurrency($id, $params));
    }
}
