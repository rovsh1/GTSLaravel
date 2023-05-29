<?php

namespace Module\Pricing\CurrencyRate\Application\Command;

use Exception;
use Module\Pricing\CurrencyRate\Domain\Service\RateManager;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CurrencyRate;
use Sdk\Module\Contracts\Bus\CommandHandlerInterface;
use Sdk\Module\Contracts\Bus\CommandInterface;

class GetRateHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly RateManager $rateCalculator
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(CommandInterface|GetRate $command): ?CurrencyRate
    {
        return $this->rateCalculator->get($command->country, $command->currency, $command->date);
    }
}
