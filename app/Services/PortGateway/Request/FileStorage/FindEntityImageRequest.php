<?php
namespace GTS\Services\PortGateway\Request\FileStorage;

class FindEntityImageRequest implements \GTS\Shared\Domain\Port\RequestInterface {

    public function __construct(
        public readonly string $fileType,
        public readonly ?int $entityId,
    ) {}

    public function module(): string {
        return 'FileStorage';
    }

    public function port(): string {
        return '';
    }

    public function method(): string {
        return 'findEntityImage';
    }

    public function arguments(): array {
        return [
            'fileType' => $this->fileType,
            'entityId' => $this->entityId,
        ];
    }
}
