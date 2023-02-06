<?php
/** ATTENTION! DO NOT MODIFY THIS FILE BECAUSE IT WAS GENERATED AUTOMATICALLY */

namespace PortGateway\FileStorage;

class GetContentsRequest implements \GTS\Shared\Domain\Port\RequestInterface {
    public function __construct(
        public readonly string $guid,
        public readonly ?int $part  = null  ,
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
