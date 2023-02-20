<?php

namespace GTS\Administrator\UI\Admin\Http\Actions\Currency;

use Illuminate\Http\Request;

use GTS\Administrator\Infrastructure\Facade\Reference\CurrencyFacadeInterface;

class StoreAction
{
    public function __construct(
        private CurrencyFacadeInterface $currencyFacade
    ) {}

    public function handle(Request $request)
    {
        // Todo точно так валидировать?
        $validated = $request->validate([
            'data.name' => 'required',
            'data.code_num' => 'required',
            'data.code_char' => 'required',
            'data.sign' => 'required'
        ]);

        // Todo точно $validated['data']?
        return $this->currencyFacade->store($validated['data']);
    }
}
