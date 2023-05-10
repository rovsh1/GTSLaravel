<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Client;

use App\Admin\Http\Requests\Client\SearchCurrenciesRequest;
use App\Admin\Models\Client\Client;
use App\Admin\Models\Reference\Currency;
use Illuminate\Http\JsonResponse;

class ClientController
{
    public function searchCurrencies(SearchCurrenciesRequest $request): JsonResponse
    {
        //@todo уточнить по поводу поля "Валюта"
        $client = Client::find($request->getClientId());
        $currency = Currency::find($client->currency_id);
        return response()->json(
            \App\Admin\Http\Resources\Currency::collection([$currency])
        );
    }
}
