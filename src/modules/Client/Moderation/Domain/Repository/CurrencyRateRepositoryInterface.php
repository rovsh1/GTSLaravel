<?php

declare(strict_types=1);

namespace Module\Client\Moderation\Domain\Repository;

use Module\Client\Shared\Domain\ValueObject\ClientId;
use Sdk\Shared\Enum\CurrencyEnum;

interface CurrencyRateRepositoryInterface
{
    public function find(ClientId $clientId, int $hotelId, CurrencyEnum $currencyTo, \DateTimeInterface $date): ?float;
}
