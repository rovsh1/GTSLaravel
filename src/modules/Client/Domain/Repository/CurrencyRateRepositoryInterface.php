<?php

declare(strict_types=1);

namespace Module\Client\Domain\Repository;

use Module\Client\Domain\ValueObject\ClientId;
use Module\Shared\Enum\CurrencyEnum;

interface CurrencyRateRepositoryInterface
{
    public function find(ClientId $clientId, int $hotelId, CurrencyEnum $currencyTo, \DateTimeInterface $date): ?float;
}
