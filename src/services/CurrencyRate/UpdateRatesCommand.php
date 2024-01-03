<?php

namespace Services\CurrencyRate;

use Illuminate\Console\Command;
use Services\CurrencyRate\UseCase\UpdateRates;

class UpdateRatesCommand extends Command
{
    protected $signature = 'system:update-currency-rates';

    protected $description = '';

    public function handle()
    {
        app(UpdateRates::class)->execute(new \DateTime());
    }
}
