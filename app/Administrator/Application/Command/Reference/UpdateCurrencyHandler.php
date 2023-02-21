<?php

namespace GTS\Administrator\Application\Command\Reference;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;

use GTS\Administrator\Domain\Repository\CurrencyRepositoryInterface;

class UpdateCurrencyHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly CurrencyRepositoryInterface $currencyRepository
    ) {}

    /**
     * @param UpdateCurrency $command
     * @return int|null
     */
    public function handle(CommandInterface $command): ?int
    {
        $currency = $this->currencyRepository->update($command->id, $command->params);

        return $currency->id ?? null;
    }
}
