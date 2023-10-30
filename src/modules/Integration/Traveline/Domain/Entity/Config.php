<?php

namespace Module\Integration\Traveline\Domain\Entity;

use Module\Integration\Traveline\Domain\Exception\UnsupportedCurrency;

class Config implements ConfigInterface
{
    public function __construct(
        private readonly array $supportedCurrencies,
    ) {}

    public function isCurrencySupported(string $currency): bool
    {
        return in_array($currency, $this->supportedCurrencies);
    }

    /**
     * @param string $currency
     * @return void
     * @throws UnsupportedCurrency
     */
    public function ensureCurrencySupported(string $currency): void
    {
        if (!$this->isCurrencySupported($currency)) {
            throw new UnsupportedCurrency("Currency {$currency} is unsupported");
        }
    }
}
