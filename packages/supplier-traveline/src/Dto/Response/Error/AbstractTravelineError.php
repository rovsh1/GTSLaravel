<?php

namespace Pkg\Supplier\Traveline\Dto\Response\Error;

abstract class AbstractTravelineError implements \JsonSerializable, TravelineResponseErrorInterface
{
    public function __construct(
        public readonly int    $code,
        public readonly string $message
    ) {}

    public function jsonSerialize(): mixed
    {
        return [
            'code' => $this->code,
            'message' => $this->message
        ];
    }
}
