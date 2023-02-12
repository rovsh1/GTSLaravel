<?php

namespace GTS\Integration\Traveline\Domain\Api\Response;

class TravelineError implements \JsonSerializable
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
