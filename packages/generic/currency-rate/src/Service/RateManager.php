<?php

namespace Pkg\CurrencyRate\Service;

use DateTime;
use Exception;
use Pkg\CurrencyRate\Contracts\ApiRepositoryInterface;
use Pkg\CurrencyRate\Contracts\CacheRepositoryInterface;
use Pkg\CurrencyRate\Contracts\DatabaseRepositoryInterface;
use Pkg\CurrencyRate\ValueObject\CountryEnum;
use Pkg\CurrencyRate\ValueObject\CurrencyRate;
use Sdk\Shared\Enum\CurrencyEnum;
use Throwable;

class RateManager
{
    private const FAILED_CACHE_TTL = 60 * 5;

    public function __construct(
        private readonly DatabaseRepositoryInterface $databaseRepository,
        private readonly CacheRepositoryInterface $cacheRepository,
        private readonly ApiRepositoryInterface $apiRepository,
    ) {
    }

    public function get(CountryEnum $country, CurrencyEnum $currency, ?DateTime $date = null): ?CurrencyRate
    {
        if ($country->currency() === $currency) {
            return new CurrencyRate($currency, 1.0, 1);
        }

        $rate = $this->cacheRepository->getRate($country, $currency, $date);
        if ($rate) {
            return $rate;
        }

        $rate = $this->databaseRepository->getRate($country, $currency, $date);
        if ($rate) {
            $this->cacheRepository->setRate($country, $rate, $date);
            return $rate;
        }

        try {
            $rates = $this->apiRepository->getCountryRates($country);
            foreach ($rates as $rate) {
                $this->cacheRepository->setRate($country, $rate, $date);
                $this->databaseRepository->setRate($country, $rate, $date);
            }

            if ($rates->has($currency)) {
                return $rates->get($currency);
            }
        } catch (Throwable $e) {
            //dd($e);
        }

        //TODO report warning notification (logManager)
        //$this->logger->exception();

        $rate = $this->databaseRepository->getLastFilledRate($country, $currency, $date);
        if ($rate) {
            $this->cacheRepository->setRate($country, $rate, $date, self::FAILED_CACHE_TTL);
            return $rate;
        }

        throw new Exception(
            'Currency [' . $country->value . '-' . $currency->value . '] rate undefined'
        );
    }

    public function update(CountryEnum $country, ?DateTime $date = null): void
    {
        $rates = $this->apiRepository->getCountryRates($country);
        foreach ($rates as $rate) {
            $this->cacheRepository->setRate($country, $rate, $date);
            $this->databaseRepository->setRate($country, $rate, $date);
        }
    }
}