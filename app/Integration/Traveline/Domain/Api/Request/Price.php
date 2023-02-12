<?php

namespace GTS\Integration\Traveline\Domain\Api\Request;

class Price
{
    public function __construct(
        public readonly int   $code,
        public readonly float $price
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['code'],
            $data['price'],
        );
    }

    /**
     * @param array $items
     * @return self[]
     */
    public static function collectionFromArray(array $items): array
    {
        return array_map(fn(array $data) => static::fromArray($data), $items);
    }
}
