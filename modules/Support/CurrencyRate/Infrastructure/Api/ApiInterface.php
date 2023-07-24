<?php

namespace Module\Support\CurrencyRate\Infrastructure\Api;

use Module\Support\CurrencyRate\Domain\ValueObject\CurrencyRatesCollection;

interface ApiInterface
{
    public function getRates(\DateTime $date = null): CurrencyRatesCollection;
}