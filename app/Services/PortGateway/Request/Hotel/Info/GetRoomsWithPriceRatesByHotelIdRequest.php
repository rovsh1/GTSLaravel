<?php

namespace PortGateway\Hotel\Info;

class GetRoomsWithPriceRatesByHotelIdRequest implements \GTS\Shared\Domain\Port\RequestInterface {

    public function __construct(
        public readonly int $id,
    ) {}

    public function module(): string {
        return 'Hotel';
    }

    public function port(): string {
        return 'Info';
    }

    public function method(): string {
        return 'getRoomsWithPriceRatesByHotelId';
    }

    public function arguments(): array {
        return [
            'id' => $this->id,
        ];
    }
}
