<?php

declare(strict_types=1);

namespace Module\Booking\Order\Application\UseCase\Admin\Guest;

use Module\Booking\Order\Application\Response\GuestDto;
use Module\Booking\Order\Domain\Repository\OrderRepositoryInterface;
use Module\Booking\Order\Domain\Repository\GuestRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class Get implements UseCaseInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly GuestRepositoryInterface $guestRepository,
    ) {}

    public function execute(int $orderId): array
    {
        $order = $this->orderRepository->find($orderId);
        if ($order === null) {
            throw new EntityNotFoundException('Order not found');
        }
        $guests = $this->guestRepository->get($order->guestIds());

        return GuestDto::collectionFromDomain($guests);
    }
}
