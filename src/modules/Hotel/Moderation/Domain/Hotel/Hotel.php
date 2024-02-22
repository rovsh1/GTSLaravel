<?php

namespace Module\Hotel\Moderation\Domain\Hotel;

use Module\Hotel\Moderation\Domain\Hotel\ValueObject\Address;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\ContactCollection;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\HotelId;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\TimeSettings;
use Sdk\Shared\Enum\CurrencyEnum;

class Hotel
{
    public function __construct(
        private readonly HotelId $id,
        private string $name,
        private CurrencyEnum $currency,
        private TimeSettings $timeSettings,
        private Address $address,
        private readonly ContactCollection $contacts
    ) {}

    public function id(): HotelId
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

    public function setTimeSettings(TimeSettings $timeSettings): void
    {
        $this->timeSettings = $timeSettings;
    }

    public function address(): Address
    {
        return $this->address;
    }

    public function contacts(): ContactCollection
    {
        return $this->contacts;
    }
}
