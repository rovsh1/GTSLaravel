<?php

namespace GTS\Administrator\UI\Admin\Http\Actions\Currency;

use GTS\Administrator\Infrastructure\Facade\Reference\CurrencyFacadeInterface;

class GetCurrencies
{
    public function __construct(private CurrencyFacadeInterface $facade) { }

    public function handle()
    {
        // Отсюда уходит DTO
        return $this->facade->getCurrencies((object)[
            'limit' => 10,
            'offset' => 0
        ]);
    }
}
