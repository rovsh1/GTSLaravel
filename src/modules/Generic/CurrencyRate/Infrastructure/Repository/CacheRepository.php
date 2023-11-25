<?php

namespace Module\Generic\CurrencyRate\Infrastructure\Repository;

use DateTime;
use Illuminate\Support\Facades\Cache;
use Module\Generic\CurrencyRate\Domain\Repository\CacheRepositoryInterface;
use Module\Generic\CurrencyRate\Domain\ValueObject\CountryEnum;
use Module\Generic\CurrencyRate\Domain\ValueObject\CurrencyRate;
use Module\Generic\CurrencyRate\Domain\ValueObject\CurrencyRatesCollection;
use Sdk\Shared\Enum\CurrencyEnum;

class CacheRepository implements CacheRepositoryInterface
{
    private const CACHE_TTL = 60 * 60 * 24;

    /**
     * @throws \Exception
     */
    public function getRates(CountryEnum $country, DateTime $date = null): CurrencyRatesCollection
    {
        $rates = [];
        foreach (CurrencyEnum::cases() as $currency) {
            $rates[] = $this->getRate($country, $currency, $date);
        }
        return new CurrencyRatesCollection($rates);
    }

    /**
     * @throws \Exception
     */
    public function getRate(CountryEnum $country, CurrencyEnum $currency, DateTime $date = null): ?CurrencyRate
    {
        $data = Cache::get(self::cacheId($country, $currency, $date ?? new DateTime()));

        return empty($data) ? null : new CurrencyRate($currency, $data['value'], $data['nominal']);
    }

    public function setRates(CountryEnum $country, CurrencyRatesCollection $rates, DateTime $date = null): void
    {
        foreach ($rates as $rate) {
            $this->setRate($country, $rate, $date);
        }
    }

    public function setRate(CountryEnum $country, CurrencyRate $rate, DateTime $date = null, int $ttl = null): void
    {
        Cache::set(
            self::cacheId($country, $rate->currency(), $date ?? new DateTime()),
            self::packRate($rate),
            $ttl ?? self::CACHE_TTL
        );
    }

    private static function packRate(CurrencyRate $rate): array
    {
        return [
            'value' => $rate->value(),
            'nominal' => $rate->nominal(),
        ];
    }

    private static function cacheId(CountryEnum $country, CurrencyEnum $currency, DateTime $date): string
    {
        return 'currency_rate:' . $country->value . '_' . $currency->value . ':' . $date->format('Y-m-d');
    }
}