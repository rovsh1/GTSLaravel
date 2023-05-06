<?php

namespace Module\Pricing\CurrencyRate\Application\Command;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Pricing\CurrencyRate\Domain\Service\RateManager;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CurrencyRate;
use Exception;

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
