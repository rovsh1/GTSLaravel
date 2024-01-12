<?php

namespace Pkg\Supplier\Traveline\Http\Response;

use Pkg\Supplier\Traveline\Http\Response\Error\TravelineResponseErrorInterface;

class ErrorResponse extends AbstractTravelineResponse
{
    public function __construct(
        /** @var TravelineResponseErrorInterface[] $errors */
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
