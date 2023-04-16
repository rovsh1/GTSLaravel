<?php

namespace Module\Pricing\CurrencyRate\Infrastructure\Repository;

use Module\Pricing\CurrencyRate\Domain\Repository\CacheRepositoryInterface;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CountryEnum;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CurrencyEnum;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CurrencyRate;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CurrencyRatesCollection;
use Illuminate\Support\Facades\Cache;
use DateTime;

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
        $data = Cache::get(self::cacheId($country, $currency, $date));

        return empty($data) ? null : new CurrencyRate($currency, (float)$data);
    }

    public function setRates(CountryEnum $country, CurrencyRatesCollection $rates, DateTime $date = null): void
    {
        foreach ($rates as $rate) {
            $this->setRate($country, $rate, $date);
        }
    }

    public function setRate(CountryEnum $country, CurrencyRate $rate, DateTime $date = null, int $ttl = null): void
    {
        Cache::add(self::cacheId($country, $rate->currency(), $date), self::packRate($rate), $ttl ?? self::CACHE_TTL);
    }

    private static function packRate(CurrencyRate $rate): string
    {
        return (string)$rate->value();
    }

    private static function cacheId(CountryEnum $country, CurrencyEnum $currency, DateTime $date): string
    {
        return 'currency_rate:' . $country->value . '_' . $currency->value . ':' . $date->format('Y-m-d');
    }
}