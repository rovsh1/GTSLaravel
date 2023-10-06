<?php

namespace Module\Pricing\Domain\Hotel;

use Module\Pricing\Domain\Hotel\ValueObject\HotelId;
use Module\Shared\Domain\ValueObject\Percent;
use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

final class Hotel extends AbstractAggregateRoot
{
    public function __construct(
        private readonly HotelId $id,
        private readonly CurrencyEnum $currency,
        private readonly Percent $vat,
        private readonly Percent $touristTax,
    ) {
    }

    public function id(): HotelId
    {
        return $this->id;
    }

    public function currency(): CurrencyEnum
    {
        return $this->currency;
    }

    public function countryCode(): string
    {
        return 'UZ';
    }

    public function vat(): Percent
    {
        return $this->vat;
    }

    public function touristTax(bool $isResident): Percent
    {
        return $this->touristTax;
    }
}
