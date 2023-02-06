<?php

namespace PortGateway\Request\FileStorage;

class GetEntityImagesRequest implements \GTS\Shared\Domain\Port\RequestInterface {

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
        return 'getEntityImages';
    }

    public function arguments(): array {
        return [
            'fileType' => $this->fileType,
            'entityId' => $this->entityId,
        ];
    }
}
