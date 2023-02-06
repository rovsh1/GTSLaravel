<?php

namespace PortGateway\FileStorage;

class GetContentsRequest implements \GTS\Shared\Domain\Port\RequestInterface {

    public function __construct(
        public readonly string $guid,
        public readonly ?int $part,
    ) {}

    public function module(): string {
        return 'FileStorage';
    }

    public function port(): string {
        return '';
    }

    public function method(): string {
        return 'getContents';
    }

    public function arguments(): array {
        return [
            'guid' => $this->guid,
            'part' => $this->part,
        ];
    }
}
