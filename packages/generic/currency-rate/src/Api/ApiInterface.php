<?php

namespace Pkg\CurrencyRate\Api;

use Pkg\CurrencyRate\ValueObject\CurrencyRatesCollection;

interface ApiInterface
{
    public function getRates(\DateTime $date = null): CurrencyRatesCollection;
}