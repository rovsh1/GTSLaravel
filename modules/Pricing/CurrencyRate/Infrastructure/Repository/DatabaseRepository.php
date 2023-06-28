<?php

namespace Module\Pricing\CurrencyRate\Infrastructure\Repository;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Module\Pricing\CurrencyRate\Domain\Repository\DatabaseRepositoryInterface;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CountryEnum;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CurrencyRate;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CurrencyRatesCollection;
use Module\Pricing\CurrencyRate\Infrastructure\Model\CurrencyRate as Model;
use Module\Shared\Enum\CurrencyEnum;

class DatabaseRepository implements DatabaseRepositoryInterface
{
    public function getRates(CountryEnum $country, DateTime $date = null): CurrencyRatesCollection
    {
        return $this->getQueryRates($country, Model::whereDate($date ?? now()));
    }

    public function getLastFilledRates(CountryEnum $country, DateTime $date = null): CurrencyRatesCollection
    {
        return $this->getQueryRates($country, Model::where('date', '<=', $date ?? now()));
    }

    public function getRate(CountryEnum $country, CurrencyEnum $currency, DateTime $date = null): ?CurrencyRate
    {
        return $this->getQueryRate(
            $country,
            $currency,
            Model::where('date', $date ?? now())
        );
    }

    public function getLastFilledRate(
        CountryEnum $country,
        CurrencyEnum $currency,
        DateTime $date = null
    ): ?CurrencyRate {
        return $this->getQueryRate(
            $country,
            $currency,
            Model::where('date', '<=', $date ?? now())
        );
    }

    public function getLastFilledDate(CountryEnum $country): DateTime
    {
        return Model::whereCountry($country)->max('date');
    }

    public function setRates(CountryEnum $country, CurrencyRatesCollection $rates, DateTime $date = null): void
    {
        DB::transaction(function () use ($country, $rates, $date) {
            foreach ($rates as $rate) {
                $this->setRate($country, $rate, $date);
            }
        });
    }

    public function setRate(CountryEnum $country, CurrencyRate $rate, DateTime $date = null): void
    {
        Model::updateOrCreate([
            'date' => ($date ?? now())->format('Y-m-d'),
            'country' => $country,
            'currency' => $rate->currency()
        ], [
            'value' => $rate->value(),
            'nominal' => $rate->nominal()
        ]);
    }

    private function getQueryRates(CountryEnum $country, $query): CurrencyRatesCollection
    {
        $rates = $query->whereCountry($country)
            ->get()
            ->map(fn($r) => new CurrencyRate($r->currency, $r->value, $r->nominal))
            ->all();
        return new CurrencyRatesCollection($rates);
    }

    private function getQueryRate(CountryEnum $country, CurrencyEnum $currency, Builder $query): ?CurrencyRate
    {
        $rate = $query
            ->whereCountry($country)
            ->whereCurrency($currency)
            ->first();
        if (null === $rate) {
            return null;
        }
        return new CurrencyRate($currency, $rate->value, $rate->nominal);
    }
}