<?php

namespace Module\Pricing\CurrencyRate\Domain\ValueObject;

use Exception;
use Module\Shared\Enum\CurrencyEnum;

class CurrencyRatesCollection implements \Iterator
{
    private int $position;
    private array $rates = [];

    /**
     * @throws Exception
     */
    public function __construct(array $rates = [])
    {
        $this->setRates($rates);
        $this->position = 0;
    }

    public function has(CurrencyEnum $currency): bool
    {
        foreach ($this->rates as $rate) {
            if ($rate->currency() === $currency) {
                return true;
            }
        }
        return false;
    }

    public function get(CurrencyEnum $currency): CurrencyRate
    {
        foreach ($this->rates as $rate) {
            if ($rate->currency() === $currency) {
                return $rate;
            }
        }
        throw new Exception('Currency [' . $currency->value . '] not found in collection');
    }

    public function isEmpty(): bool
    {
        return empty($this->rates);
    }

    /**
     * @throws Exception
     */
    public function setRates(array $rates): void
    {
        $this->rates = $this->validateRates($rates);
    }

    /**
     * @throws Exception
     */
    private function validateRates(array $rates): array
    {
        foreach ($rates as $rate) {
            if (!$rate instanceof CurrencyRate) {
                throw new Exception('Currency rate need to be CurrencyRate instance');
            }
        }
        return $rates;
    }

    public function current(): CurrencyRate
    {
        return $this->rates[$this->position];
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->rates[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }
}