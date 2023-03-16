<?php

namespace App\Core\Support\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

class AjaxDataResponse implements Responsable
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
