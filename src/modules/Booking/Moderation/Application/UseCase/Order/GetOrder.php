<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\Order;

use Module\Booking\Moderation\Application\Dto\OrderDto;
use Module\Booking\Moderation\Application\Factory\OrderDtoFactory;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetOrder implements UseCaseInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $repository,
        private readonly OrderDtoFactory $factory,
    ) {}

    public function execute(int $id): ?OrderDto
    {
        $order = $this->repository->find(new OrderId($id));
        if (!$order) {
            return null;
        }

        return $this->factory->createFromEntity($order);
    }
}
