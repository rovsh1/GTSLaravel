<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Application\UseCase;

use Pkg\Booking\Requesting\Domain\Repository\RequestRepositoryInterface;
use Pkg\Booking\Requesting\Domain\ValueObject\RequestId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Sdk\Shared\Dto\FileInfoDto;

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
