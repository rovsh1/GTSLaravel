<?php

namespace PortGateway\FileStorage;

class PutRequest implements \GTS\Shared\Domain\Port\RequestInterface {

    public function __construct(
        public readonly string $guid,
        public readonly string $contents,
    ) {}

    public function module(): string {
        return 'FileStorage';
    }

    public function port(): string {
        return '';
    }

    public function method(): string {
        return 'put';
    }

    public function arguments(): array {
        return [
            'guid' => $this->guid,
            'contents' => $this->contents,
        ];
    }
}
