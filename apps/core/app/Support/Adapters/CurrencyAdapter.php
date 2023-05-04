<?php

namespace App\Core\Support\Adapters;

class CurrencyAdapter extends AbstractModuleAdapter
{
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

    protected function getModuleKey(): string
    {
        return 'CurrencyRate';
    }
}