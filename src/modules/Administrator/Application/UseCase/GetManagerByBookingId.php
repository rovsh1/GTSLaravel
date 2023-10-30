<?php

declare(strict_types=1);

namespace Module\Administrator\Application\UseCase;

use Module\Administrator\Application\Response\AdministratorDto;
use Module\Administrator\Domain\Repository\AdministratorRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetManagerByBookingId implements UseCaseInterface
{
    public function __construct(
        private readonly AdministratorRepositoryInterface $repository,
    ) {}

    public function execute(int $id): ?AdministratorDto
    {
        $administrator = $this->repository->findByBookingId($id);
        if ($administrator === null) {
            return null;
        }

        return AdministratorDto::fromDomain($administrator);
    }
}
