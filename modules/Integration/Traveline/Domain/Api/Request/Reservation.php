<?php

namespace Module\Integration\Traveline\Domain\Api\Request;

class Reservation
{
    public function __construct(
        public readonly int $number,
        public readonly string $externalNumber,
        public readonly ReservationStatusEnum $status,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['number'],
            $data['externalNumber'],
            ReservationStatusEnum::from($data['status']),
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
