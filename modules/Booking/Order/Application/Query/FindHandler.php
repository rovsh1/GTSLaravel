<?php

namespace Module\Booking\Order\Application\Query;

use Module\Booking\Order\Application\Response\OrderDto;
use Module\Booking\Order\Application\Factory\OrderDtoFactory;
use Module\Booking\Order\Domain\Repository\OrderRepositoryInterface;
use Sdk\Module\Contracts\Bus\QueryHandlerInterface;
use Sdk\Module\Contracts\Bus\QueryInterface;

class FindHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $repository,
        private readonly OrderDtoFactory $factory,
    ) {}

    public function handle(QueryInterface|Find $query): ?OrderDto
    {
        $order = $this->repository->find($query->id);
        if (!$order) {
            return null;
        }

        return $this->factory->createFromEntity($order);
    }
}
