<?php

namespace App\Admin\Http\Actions\Currency;

use Module\Administrator\Infrastructure\Facade\Reference\CurrencyFacadeInterface;
use Illuminate\Http\Request;

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
        $currencyId = $this->currencyFacade->store($validated['data']);

        if ($currencyId !== null) {
            return redirect(route('currency.index'));
        }

        // Todo редирект на ошибку?
        return redirect(route('index'));
    }
}
