<?php

declare(strict_types=1);

namespace Module\Booking\Application\UseCase\Admin\Order;

use Module\Booking\Application\Dto\OrderDto;
use Module\Booking\Application\Factory\OrderDtoFactory;
use Module\Booking\Domain\Order\Repository\OrderRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetOrder implements UseCaseInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $repository,
        private readonly OrderDtoFactory $factory,
    ) {}

    public function execute(int $id): ?OrderDto
    {
        $order = $this->repository->find($id);
        if (!$order) {
            return null;
        }

        return $this->factory->createFromEntity($order);
    }
}
