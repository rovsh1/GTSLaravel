<?php

namespace Module\Integration\Traveline\Domain\Entity;

use Module\Integration\Traveline\Domain\Exception\UnsupportedCurrency;

interface ConfigInterface
{
    public function getDefaultCurrency(): string;

    public function isCurrencyAllowed(string $currency): bool;

    /**
     * @param string $currency
     * @return void
     * @throws UnsupportedCurrency
     */
    public function ensureCurrencyAllowed(string $currency): void;
}
