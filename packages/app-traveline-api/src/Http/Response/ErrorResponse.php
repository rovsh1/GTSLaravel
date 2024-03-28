<?php

namespace Pkg\App\Traveline\Http\Response;

use Pkg\Supplier\Traveline\Dto\Response\Error\TravelineResponseErrorInterface;

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
            'success' => $this->success,
            'errors' => $this->errors
        ];
    }
}
