<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Support\UseCase\Admin\Request;

use Module\Booking\Common\Domain\Repository\RequestRepositoryInterface;
use Module\Booking\Common\Domain\ValueObject\RequestId;
use Module\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Module\Support\FileStorage\Application\Dto\FileInfoDto;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

abstract class GetDocumentFileInfo implements UseCaseInterface
{
    public function __construct(
        private readonly RequestRepositoryInterface $requestRepository,
        private readonly FileStorageAdapterInterface $fileStorageAdapter
    ) {
    }

    public function execute(int $requestId): ?FileInfoDto
    {
        $request = $this->requestRepository->find(new RequestId($requestId));

        return $this->fileStorageAdapter->getInfo($request->file()->guid());
    }
}
