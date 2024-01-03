<?php

namespace Services\CurrencyRate\UseCase;

use Exception;
use Services\CurrencyRate\Dto\GetRateDto;
use Services\CurrencyRate\Service\RateManager;

class GetNetRate
{
    public function __construct(
        private readonly RateManager $rateCalculator
    ) {}

    /**
     * @throws Exception
     */
    public function execute(GetRateDto $request): ?float
    {
        $rate = $this->rateCalculator->get($request->country, $request->currency, $request->date);

        return $rate ? $rate->rate() : null;
    }
}
