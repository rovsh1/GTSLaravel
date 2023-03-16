<?php

namespace App\Core\Support\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

class AjaxRedirectResponse implements Responsable
{
    public function __construct(private readonly string $url) {}

    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'action' => 'redirect',
            'url' => $this->url
        ]);
    }
}
