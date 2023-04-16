<?php

namespace Module\Pricing\CurrencyRate\Infrastructure\Api;

use Module\Pricing\CurrencyRate\Domain\ValueObject\CurrencyRatesCollection;

interface ApiInterface
{
    public function getRates(\DateTime $date = null): CurrencyRatesCollection;
}