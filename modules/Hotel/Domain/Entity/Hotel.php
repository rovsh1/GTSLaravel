<?php

namespace Module\Hotel\Domain\Entity;

use Module\Hotel\Domain\ValueObject\TimeSettings;
use Module\Shared\Domain\ValueObject\Id;
use Module\Shared\Enum\CurrencyEnum;

class Hotel
{
    public function __construct(
        private readonly Id $id,
        private string $name,
        private CurrencyEnum $currency,
        private TimeSettings $timeSettings,
    ) {}

    public function id(): Id
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function currency(): CurrencyEnum
    {
        return $this->currency;
    }

    public function timeSettings(): TimeSettings
    {
        return $this->timeSettings;
    }
}
