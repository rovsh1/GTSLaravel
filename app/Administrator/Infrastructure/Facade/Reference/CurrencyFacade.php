<?php

namespace GTS\Administrator\Infrastructure\Facade\Reference;

use Custom\Framework\Contracts\Bus\QueryBusInterface;

use GTS\Administrator\Domain\Repository\CurrencyRepositoryInterface;
use GTS\Administrator\Application\Query\GetCurrencies;

class CurrencyFacade implements CurrencyFacadeInterface
{
    public function __construct(
        private readonly CurrencyRepositoryInterface $currencyRepository,
        private readonly QueryBusInterface $queryBus
    ) {}

    public function findById()
    {
        return $this->currencyRepository->find();
    }

    public function getCurrencies($params)
    {
        // Из DTO формируем запрос
        return $this->queryBus->execute(new GetCurrencies($params->limit, $params->offset));
    }
}
