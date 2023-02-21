<?php

namespace GTS\Administrator\Application\Command\Reference;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;

use GTS\Administrator\Domain\Repository\CurrencyRepositoryInterface;

class StoreCurrencyHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly CurrencyRepositoryInterface $currencyRepository
    ) {}

    /**
     * @param StoreCurrency $command
     * @return int|null
     */
    public function handle(CommandInterface $command): ?int
    {
        $currency = $this->currencyRepository->create($command->params);

        return $currency->id ?? null;
    }
}
