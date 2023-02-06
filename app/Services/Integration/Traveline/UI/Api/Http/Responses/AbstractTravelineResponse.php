<?php

namespace GTS\Services\Integration\Traveline\UI\Api\Http\Responses;

abstract class AbstractTravelineResponse implements \JsonSerializable
{
    public function __construct(
        public readonly mixed $data,
        public readonly bool  $status = true,
    ) {}

    public function jsonSerialize(): mixed
    {
        return [
            'status' => $this->status,
            'data' => $this->data
        ];
    }
}
