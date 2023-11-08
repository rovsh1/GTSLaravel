<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\Order\Guest;

use Module\Booking\Moderation\Application\Dto\GuestDto;
use Module\Booking\Shared\Domain\Guest\Repository\GuestRepositoryInterface;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Get implements UseCaseInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly GuestRepositoryInterface $guestRepository,
    ) {}

    public function execute(int $orderId): array
    {
        $order = $this->orderRepository->findOrFail(new OrderId($orderId));
        $guests = $this->guestRepository->get($order->guestIds());

        return GuestDto::collectionFromDomain($guests);
    }
}
