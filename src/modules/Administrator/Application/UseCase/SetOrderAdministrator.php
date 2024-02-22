<?php

declare(strict_types=1);

namespace Module\Administrator\Application\UseCase;

use Module\Administrator\Domain\Repository\OrderAdministratorRepositoryInterface;
use Module\Administrator\Domain\ValueObject\AdministratorId;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetOrderAdministrator implements UseCaseInterface
{
    public function __construct(
        private readonly OrderAdministratorRepositoryInterface $orderAdministratorRepository
    ) {}

    public function execute(int $orderId, int $administratorId): void
    {
        $this->orderAdministratorRepository->set(new OrderId($orderId), new AdministratorId($administratorId));
    }
}
