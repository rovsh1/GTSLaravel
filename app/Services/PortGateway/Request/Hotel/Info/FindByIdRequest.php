<?php
/** ATTENTION! DO NOT MODIFY THIS FILE BECAUSE IT WAS GENERATED AUTOMATICALLY */

namespace PortGateway\Hotel\Info;

class FindByIdRequest implements \GTS\Shared\Domain\Port\RequestInterface {
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
        return 'findById';
    }

    public function arguments(): array {
        return [
            'id' => $this->id,
        ];
    }
}
