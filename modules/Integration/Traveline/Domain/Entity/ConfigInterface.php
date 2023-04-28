<?php

namespace Module\Integration\Traveline\Domain\Entity;

use Module\Integration\Traveline\Domain\Exception\UnsupportedCurrency;

interface ConfigInterface
{
    public function isCurrencySupported(string $currency): bool;

    /**
     * @param string $currency
     * @return void
     * @throws UnsupportedCurrency
     */
    public function ensureCurrencySupported(string $currency): void;
}
