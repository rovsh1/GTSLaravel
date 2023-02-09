<?php

namespace GTS\Administrator\UI\Admin\Http\Actions\Currency;

use GTS\Administrator\Infrastructure\Facade\Reference\CurrencyFacadeInterface;

class ViewAction
{
    public function __construct(
        private CurrencyFacadeInterface $currencyFacade
    ) {}

    public function handle()
    {
        //
    }
}
