<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Middleware;

use Module\Booking\Moderation\Domain\Booking\Exception\OrderModeratingNotAllowed;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Sdk\Booking\Contracts\Entity\BookingPartInterface;

class EnsureBookingEditableMiddleware
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly BookingRepositoryInterface $bookingRepository
    ) {}

    /**
     * @param Booking|BookingPartInterface $orderId
     * @param \Closure $closure
     * @return mixed
     * @throws OrderModeratingNotAllowed
     */
    public function handle(Booking|BookingPartInterface $entity, \Closure $closure): mixed
    {
        if ($entity instanceof Booking) {
            $orderId = $entity->orderId();
        } else {
            $booking = $this->bookingRepository->findOrFail($entity->bookingId());
            $orderId = $booking->orderId();
        }

        $order = $this->orderRepository->findOrFail($orderId);
        if (!$order->inModeration()) {
            throw new OrderModeratingNotAllowed();
        }

        return $closure($entity);
    }
}
