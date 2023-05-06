<?php

namespace Module\Pricing\CurrencyRate\Application\Command;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Pricing\CurrencyRate\Domain\Service\RateManager;
use Throwable;
use Exception;

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
