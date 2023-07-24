<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Support\UseCase\Admin\Request;

use Module\Booking\Common\Domain\Adapter\FileStorageAdapterInterface;
use Module\Booking\Common\Domain\Entity\Request;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

abstract class GetDocumentFileInfo implements UseCaseInterface
{
    public function __construct(
        private readonly FileStorageAdapterInterface $fileStorageAdapter
    ) {}

    public function execute(int $requestId)
    {
        return $this->fileStorageAdapter->getEntityFile($requestId, $this->getFileType());
    }

    /**
     * @return class-string
     */
    abstract protected function getFileType(): string;
}
