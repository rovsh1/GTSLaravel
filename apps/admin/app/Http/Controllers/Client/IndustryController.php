<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Client;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Resources\Client\Industry;
use App\Admin\Models\Client\Legal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IndustryController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        return response()->json(
            Industry::collection(
                Legal\Industry::get()
            )
        );
    }
}
