<?php

declare(strict_types=1);

namespace App\Hotel\Http\Controllers\Reference;

use App\Hotel\Http\Resources\Currency as CurrencyResource;
use App\Hotel\Models\Reference\Currency;
use App\Hotel\Support\Http\AbstractController;
use Illuminate\Http\JsonResponse;

class CurrenciesController extends AbstractController
{
    public function list(): JsonResponse
    {
        return response()->json(
            CurrencyResource::collection(
                Currency::all()
            )
        );
    }
}
