<?php

namespace Pkg\Supplier\Traveline\Dto\Request;

use Pkg\Supplier\Traveline\Models\TravelineReservationStatusEnum;

class Reservation
{
    public function __construct(
        public readonly int $number,
        public readonly string $externalNumber,
        public readonly TravelineReservationStatusEnum $status,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['number'],
            $data['externalNumber'],
            TravelineReservationStatusEnum::from($data['status']),
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
