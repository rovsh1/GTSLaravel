<?php

namespace App\Core\Support\Adapters;

class CurrencyAdapter extends AbstractPortAdapter
{
    protected string $module = 'CurrencyRate';

    public function getRate(string $currency, string $country = null): float
    {
        return $this->request('rate', [
            'country' => 'UZ',
            'currency' => $currency
            //'date' => $date
        ]);
    }

    public function updateRates(string|\DateTime $date = null): void
    {
        $this->request('update', [
            'date' => $date
        ]);
    }

    public function updateCountryRates(string $country, string|\DateTime $date = null): void
    {
        $this->request('update-country', [
            'country' => $country,
            'date' => $date
        ]);
    }
}