<?php

namespace Pkg\Supplier\Traveline\Http\Response;

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
