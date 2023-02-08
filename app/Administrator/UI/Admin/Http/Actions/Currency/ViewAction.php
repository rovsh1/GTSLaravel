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
        // Отсюда уходит DTO
        return $this->currencyFacade->getCurrencies((object)[
            'limit' => 10,
            'offset' => 0
        ]);
    }
}
