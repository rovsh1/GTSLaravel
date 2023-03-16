<?php

namespace App\Core\Support\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

class AjaxReloadResponse implements Responsable
{
    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'action' => 'reload'
        ]);
    }
}
