<?php

declare(strict_types=1);

namespace Module\Client\Moderation\Infrastructure\Repository;

use Module\Client\Moderation\Domain\Repository\CurrencyRateRepositoryInterface;
use Module\Client\Moderation\Infrastructure\Models\CurrencyRate;
use Module\Client\Shared\Domain\ValueObject\ClientId;
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