<?php

namespace GTS\Administrator\Infrastructure\Facade\Reference;

use Custom\Framework\Contracts\Bus\CommandBusInterface;
use Custom\Framework\Contracts\Bus\QueryBusInterface;

use GTS\Administrator\Application\Command\Reference\StoreCurrency;
use GTS\Administrator\Domain\Repository\CurrencyRepositoryInterface;

class CurrencyFacade implements CurrencyFacadeInterface
{
    public function __construct(
        private readonly CurrencyRepositoryInterface $currencyRepository,
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
    ) {}

    public function findById(int $id)
    {
        return $this->currencyRepository->find($id);
    }

    public function findByIdWithTranslations(int $id)
    {
        $this->queryBus->execute();
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

    public function store(array $params)
    {
        return $this->commandBus->execute(
            new StoreCurrency(
                $params['name'],
                $params['code_num'],
                $params['code_char'],
                $params['sign']
            )
        );
    }
}
