<?php

namespace App\Shared\Http\Responses;

use Illuminate\Http\JsonResponse;

class AjaxDataResponse implements AjaxResponseInterface
{
    public function __construct(private readonly mixed $data) {}

    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->data
        ]);
    }
}
