<?php
namespace GTS\Services\PortGateway\Request\FileStorage;

class FileInfoRequest implements \GTS\Shared\Domain\Port\RequestInterface {

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
        return 'fileInfo';
    }

    public function arguments(): array {
        return [
            'guid' => $this->guid,
            'part' => $this->part,
        ];
    }
}
