<?php

namespace Module\Pricing\CurrencyRate\Application\Command;

use Exception;
use Module\Pricing\CurrencyRate\Domain\Service\RateManager;
use Sdk\Module\Contracts\Bus\CommandHandlerInterface;
use Sdk\Module\Contracts\Bus\CommandInterface;
use Throwable;

class UpdateRatesHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly RateManager $rateManager,
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(CommandInterface|GetRate $command): void
    {
        try {
            $this->rateManager->update($command->country, $command->date);
        } catch (Throwable $e) {
            throw new Exception('Currency [' . $command->country->value . '] update failed', 0, $e);
        }
    }
}
