<?php

namespace Module\Booking\Application\Admin\Order\Query;

use Module\Booking\Application\Admin\Order\Factory\OrderDtoFactory;
use Module\Booking\Application\Admin\Order\Response\OrderDto;
use Module\Booking\Domain\Order\Repository\OrderRepositoryInterface;
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
