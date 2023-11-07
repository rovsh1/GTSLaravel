<?php

declare(strict_types=1);

namespace Module\Booking\Application\UseCase\Admin\Order;

use Module\Booking\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Order\ValueObject\OrderId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetOrderBookings implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository
    ) {}

    public function execute(int $orderId): array
    {
        $bookings = $this->bookingRepository->getByOrderId(new OrderId($orderId));

        return [];
    }
}
