<?php
namespace GTS\Services\PortGateway\Request\FileStorage;

class DeleteRequest implements \GTS\Shared\Domain\Port\RequestInterface {

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
        return 'delete';
    }

    public function arguments(): array {
        return [
            'guid' => $this->guid,
        ];
    }
}
