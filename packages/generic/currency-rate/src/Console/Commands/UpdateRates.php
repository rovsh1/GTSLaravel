<?php

namespace Pkg\CurrencyRate\Console\Commands;

use Illuminate\Console\Command;
use Pkg\CurrencyRate\UseCase\UpdateRates as UseCase;

class UpdateRates extends Command
{
    protected $signature = 'system:update-currency-rates';

    protected $description = '';

    public function handle(): void
    {
        app(UseCase::class)->execute(new \DateTime());
    }
}
