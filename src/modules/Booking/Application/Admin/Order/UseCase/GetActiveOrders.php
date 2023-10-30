<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\Order\UseCase;

use Module\Booking\Application\Admin\Order\Factory\OrderDtoFactory;
use Module\Booking\Domain\Order\Repository\OrderRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetActiveOrders implements UseCaseInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $repository,
        private readonly OrderDtoFactory $factory,
    ) {}

    public function execute(int|null $clientId): array
    {
        $orders = $this->repository->getActiveOrders($clientId);

        return $this->factory->createCollection($orders);
    }
}
