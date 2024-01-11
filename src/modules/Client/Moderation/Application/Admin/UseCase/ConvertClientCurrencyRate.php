<?php

declare(strict_types=1);

namespace Module\Client\Moderation\Application\Admin\UseCase;

use Module\Client\Moderation\Domain\Repository\CurrencyRateRepositoryInterface;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Enum\CurrencyEnum;
use Shared\Contracts\Adapter\CurrencyRateAdapterInterface;

class ConvertClientCurrencyRate implements UseCaseInterface
{
    public function __construct(
        private readonly CurrencyRateRepositoryInterface $currencyRateRepository,
        private readonly CurrencyRateAdapterInterface $currencyRateAdapter
    ) {
    }

    public function execute(
        int $clientId,
        int $hotelId,
        int|float $price,
        CurrencyEnum $currencyFrom,
        CurrencyEnum $currencyTo,
        \DateTimeInterface $date,
        ?string $country = null,
    ): float {
        $clientCurrencyRate = $this->currencyRateRepository->find(new ClientId($clientId), $hotelId, $currencyTo, $date);
        if ($clientCurrencyRate === null) {
            return $this->currencyRateAdapter->convertNetRate($price, $currencyFrom, $currencyTo, $country, $date);
        }

        if ($currencyFrom !== CurrencyEnum::UZS) {
            throw new \DomainException("Can't convert from currency [{$currencyFrom->name}]");
        }

        return $price / $clientCurrencyRate;
    }
}
