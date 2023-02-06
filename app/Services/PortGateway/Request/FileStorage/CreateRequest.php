<?php

namespace PortGateway\Request\FileStorage;

class CreateRequest implements \GTS\Shared\Domain\Port\RequestInterface {

    public function __construct(
        public readonly string $fileType,
        public readonly ?int $entityId,
        public readonly ?string $name,
    ) {}

    public function module(): string {
        return 'FileStorage';
    }

    public function port(): string {
        return '';
    }

    public function method(): string {
        return 'create';
    }

    public function arguments(): array {
        return [
            'fileType' => $this->fileType,
            'entityId' => $this->entityId,
            'name' => $this->name,
        ];
    }
}
