<?php

namespace Module\Pricing\CurrencyRate\Application\UseCase;

use Exception;
use Module\Pricing\CurrencyRate\Application\Request\GetRateDto;
use Module\Pricing\CurrencyRate\Domain\Service\RateManager;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetRate implements UseCaseInterface
{
    public function __construct(
        private readonly RateManager $rateCalculator
    ) {
    }

    /**
     * @throws Exception
     */
    public function execute(GetRateDto $request): ?float
    {
        $rate = $this->rateCalculator->get($request->country, $request->currency, $request->date);

        return $rate ? $rate->rate() : null;
    }
}
