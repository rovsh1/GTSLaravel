<?php

declare(strict_types=1);

namespace Module\Booking\Order\Application\UseCase\Admin\Tourist;

use Module\Booking\Order\Application\Response\TouristDto;
use Module\Booking\Order\Domain\Repository\OrderRepositoryInterface;
use Module\Booking\Order\Domain\Repository\TouristRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class Get implements UseCaseInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly TouristRepositoryInterface $touristRepository,
    ) {}

    public function execute(int $orderId): array
    {
        $order = $this->orderRepository->find($orderId);
        if ($order === null) {
            throw new EntityNotFoundException('Order not found');
        }
        $tourists = $this->touristRepository->get($order->touristIds());

        return TouristDto::collectionFromDomain($tourists);
    }
}
