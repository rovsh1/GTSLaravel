<?php

namespace App\Admin\Console\Commands\System;

use App\Core\Support\Adapters\CurrencyAdapter;
use Illuminate\Console\Command;

class UpdateCurrencyRates extends Command
{
    protected $signature = 'system:update-currency-rates';

    protected $description = '';

    public function handle()
    {
        /** @var CurrencyAdapter $adapter */
        $adapter = app(CurrencyAdapter::class);
        $adapter->updateRates();
    }
}
