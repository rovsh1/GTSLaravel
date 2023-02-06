<?php
/** ATTENTION! DO NOT MODIFY THIS FILE BECAUSE IT WAS GENERATED AUTOMATICALLY */

namespace PortGateway\FileStorage;

class FindRequest implements \GTS\Shared\Domain\Port\RequestInterface {
    public function __construct(
        public readonly string $guid,
    ) {}

    public function module(): string {
        return 'FileStorage';
    }

    public function port(): string {
        return '';
    }

    public function method(): string {
        return 'find';
    }

    public function arguments(): array {
        return [
            'guid' => $this->guid,
        ];
    }
}
