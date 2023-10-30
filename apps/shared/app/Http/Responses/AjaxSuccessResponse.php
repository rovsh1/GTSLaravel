<?php

namespace App\Shared\Http\Responses;

use Illuminate\Http\JsonResponse;

class AjaxSuccessResponse implements AjaxResponseInterface
{
    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => []
        ]);
    }
}
