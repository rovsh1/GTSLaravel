<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Application\UseCase;

use Module\Supplier\Moderation\Application\Response\ServiceDto;
use Module\Supplier\Moderation\Domain\Supplier\Repository\ServiceRepositoryInterface;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\ServiceId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class FindService implements UseCaseInterface
{
    public function __construct(
        private readonly ServiceRepositoryInterface $repository
    ) {}

    public function execute(int $id): ?ServiceDto
    {
        $service = $this->repository->find(new ServiceId($id));
        if ($service === null) {
            return null;
        }

        return new ServiceDto(
            $service->id()->value(),
            $service->supplierId()->value(),
            $service->title(),
            $service->type()
        );
    }
}
