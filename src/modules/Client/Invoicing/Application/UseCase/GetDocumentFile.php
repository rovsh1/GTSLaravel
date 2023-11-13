<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Application\UseCase;

use Module\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Module\Shared\Dto\FileDto;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetDocumentFile implements UseCaseInterface
{
    public function __construct(
        private readonly FileStorageAdapterInterface $fileStorageAdapter
    ) {}

    public function execute(string $guid): FileDto
    {
        return $this->fileStorageAdapter->find($guid);
    }
}
