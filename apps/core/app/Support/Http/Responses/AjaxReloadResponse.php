<?php

namespace App\Core\Support\Http\Responses;

use Illuminate\Http\JsonResponse;

class AjaxReloadResponse implements AjaxResponseInterface
{
    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'action' => 'reload'
        ]);
    }
}
