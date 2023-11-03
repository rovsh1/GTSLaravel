<?php

declare(strict_types=1);

namespace Module\Client\Infrastructure\Repository;

use Module\Client\Domain\Repository\CurrencyRateRepositoryInterface;
use Module\Client\Domain\ValueObject\ClientId;
use Module\Client\Infrastructure\Models\CurrencyRate;
use Module\Shared\Enum\CurrencyEnum;

class CurrencyRateRepository implements CurrencyRateRepositoryInterface
{
    public function find(ClientId $clientId, int $hotelId, CurrencyEnum $currencyTo, \DateTimeInterface $date): ?float
    {
        $model = CurrencyRate::whereClientId($clientId->value())
            ->whereIncludeDate($date)
            ->whereHotelId($hotelId)
            ->whereCurrency($currencyTo)
            ->first();

        return $model?->rate;
    }
}
