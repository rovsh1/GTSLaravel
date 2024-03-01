<?php

namespace Pkg\Supplier\Traveline\Http\Response;

abstract class AbstractTravelineResponse implements \JsonSerializable
{
    public function __construct(
        public readonly mixed $data,
        public readonly bool  $success = true,
    ) {}

    public function jsonSerialize(): mixed
    {
        return [
            'success' => $this->success,
            'data' => $this->data
        ];
    }
}
