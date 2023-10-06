<?php

declare(strict_types=1);

namespace Module\Pricing\Domain\HotelPricing\Service;

use Module\Shared\Domain\ValueObject\Percent;
use Module\Shared\Enum\CurrencyEnum;

class HotelRoomValuesStorage
{
    public function getVatPercent(int $roomId): Percent {}

    public function getTouristTaxPercent(int $roomId): Percent {}

    public function getHotelCurrency(int $roomId): CurrencyEnum {}
}
