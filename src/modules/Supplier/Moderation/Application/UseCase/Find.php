<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Application\UseCase;

use Module\Supplier\Moderation\Application\Response\SupplierDto;
use Module\Supplier\Moderation\Domain\Supplier\Repository\SupplierRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Find implements UseCaseInterface
{
    public function __construct(
        private readonly SupplierRepositoryInterface $repository
    ) {}

    public function execute(int $id): ?SupplierDto
    {
        $supplier = $this->repository->find($id);
        if ($supplier === null) {
            return null;
        }

        return SupplierDto::fromDomain($supplier);
    }
}
