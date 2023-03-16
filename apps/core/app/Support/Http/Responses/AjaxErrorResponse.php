<?php

namespace App\Core\Support\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

class AjaxErrorResponse implements Responsable
{
    public function __construct(private readonly string $message) {}

    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => $this->message
        ]);
    }
}
