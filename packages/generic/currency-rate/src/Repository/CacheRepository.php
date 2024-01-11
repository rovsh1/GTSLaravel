<?php

namespace Pkg\CurrencyRate\Repository;

use DateTime;
use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Redis;
use Pkg\CurrencyRate\Contracts\CacheRepositoryInterface;
use Pkg\CurrencyRate\ValueObject\CountryEnum;
use Pkg\CurrencyRate\ValueObject\CurrencyRate;
use Pkg\CurrencyRate\ValueObject\CurrencyRatesCollection;
use Sdk\Shared\Enum\CurrencyEnum;

class CacheRepository implements CacheRepositoryInterface
{
    private const CACHE_TTL = 60 * 60 * 24;
    private const CACHE_KEY = 'currency-rates';

    private Connection $connection;

    public function __construct()
    {
        $this->connection = Redis::connection('cache');
    }

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
        $encoded = $this->connection->hGet(
            $this->cacheKey($date ?? new DateTime()),
            $this->cacheField($country, $currency)
        );
        $data = json_decode($encoded, true);

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
        $cacheId = $this->cacheKey($date ?? new DateTime());
        $this->connection->hSet(
            $cacheId,
            $this->cacheField($country, $rate->currency()),
            json_encode(self::packRate($rate))
        );
        $this->connection->expire($cacheId, $ttl ?? self::CACHE_TTL);
    }

    private static function packRate(CurrencyRate $rate): array
    {
        return [
            'value' => $rate->value(),
            'nominal' => $rate->nominal(),
        ];
    }

    private function cacheKey(DateTime $date): string
    {
        return self::CACHE_KEY . ":{$date->format('Y-m-d')}";
    }

    private function cacheField(CountryEnum $country, CurrencyEnum $currency): string
    {
        return "$country->value:$currency->value";
    }
}