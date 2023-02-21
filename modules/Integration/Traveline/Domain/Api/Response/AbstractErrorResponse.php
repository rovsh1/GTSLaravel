<?php

namespace Module\Integration\Traveline\Domain\Api\Response;

use Module\Integration\Traveline\Domain\Api\Response\Error\AbstractTravelineError;

abstract class AbstractErrorResponse extends AbstractTravelineResponse
{
    public function __construct(
        /** @var AbstractTravelineError[] $errors */
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
