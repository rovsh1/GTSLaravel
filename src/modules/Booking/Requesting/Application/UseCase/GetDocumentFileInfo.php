<?php

declare(strict_types=1);

namespace Module\Booking\Requesting\Application\UseCase;

use Module\Booking\Requesting\Domain\Repository\RequestRepositoryInterface;
use Module\Booking\Requesting\Domain\ValueObject\RequestId;
use Module\Support\FileStorage\Application\Dto\FileInfoDto;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;

class GetDocumentFileInfo implements UseCaseInterface
{
    public function __construct(
        private readonly RequestRepositoryInterface $requestRepository,
        private readonly FileStorageAdapterInterface $fileStorageAdapter
    ) {}

    public function execute(int $requestId): ?FileInfoDto
    {
        $request = $this->requestRepository->find(new RequestId($requestId));

        return $this->fileStorageAdapter->getInfo($request->file()->guid());
    }
}
