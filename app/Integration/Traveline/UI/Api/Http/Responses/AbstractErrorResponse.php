<?php

namespace GTS\Integration\Traveline\UI\Api\Http\Responses;

class AbstractErrorResponse extends AbstractTravelineResponse
{
    public function __construct(
        /** @var TravelineError[] $errors */
        public readonly array $errors
    )
    {
        parent::__construct(null, false);
    }

    public function jsonSerialize(): mixed
    {
        return [
            'status' => $this->status,
            'errors' => $this->errors
        ];
    }
}
