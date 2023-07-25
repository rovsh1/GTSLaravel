<?php

namespace Module\Generic\CurrencyRate\Infrastructure\Api;

use Module\Generic\CurrencyRate\Domain\ValueObject\CurrencyRatesCollection;

interface ApiInterface
{
    public function getRates(\DateTime $date = null): CurrencyRatesCollection;
}