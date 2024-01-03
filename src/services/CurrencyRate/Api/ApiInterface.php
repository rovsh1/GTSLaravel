<?php

namespace Services\CurrencyRate\Api;

use Services\CurrencyRate\ValueObject\CurrencyRatesCollection;

interface ApiInterface
{
    public function getRates(\DateTime $date = null): CurrencyRatesCollection;
}