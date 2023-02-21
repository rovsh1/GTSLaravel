<?php

namespace App\Admin\Http\Actions\Currency;

use Module\Administrator\Infrastructure\Facade\Reference\CurrencyFacadeInterface;
use Illuminate\Http\Request;

class DeleteAction
{
    public function __construct(
        private CurrencyFacadeInterface $currencyFacade
    ) {}

    public function handle(int $id, Request $request)
    {
        dd($id);
        // Todo точно так валидировать?
        $validated = $request->validate([
            'data.name' => 'required',
            'data.code_num' => 'required',
            'data.code_char' => 'required',
            'data.sign' => 'required'
        ]);

        // Todo точно $validated['data']?
        $currencyId = $this->currencyFacade->update($id, $validated['data']);

        if ($currencyId !== null) {
            return redirect(route('currency.index'));
        }

        // Todo редирект на ошибку?
        return redirect(route('index'));
    }
}
