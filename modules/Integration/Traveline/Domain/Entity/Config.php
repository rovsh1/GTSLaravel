<?php

namespace Module\Integration\Traveline\Domain\Entity;

use Module\Integration\Traveline\Domain\Exception\UnsupportedCurrency;

class Config implements ConfigInterface
{
    public const allowedCurrencies = ['UZS'];

    public function __construct(
        private readonly string $defaultCurrency,
    ) {}

    public function getDefaultCurrency(): string
    {
        return $this->defaultCurrency;
    }

    public function isCurrencyAllowed(string $currency): bool
    {
        return in_array($currency, self::allowedCurrencies);
    }

    /**
     * @param string $currency
     * @return void
     * @throws UnsupportedCurrency
     */
    public function ensureCurrencyAllowed(string $currency): void
    {
        if (!$this->isCurrencyAllowed($currency)) {
            throw new UnsupportedCurrency("Currency {$currency} is unsupported");
        }
    }
}
