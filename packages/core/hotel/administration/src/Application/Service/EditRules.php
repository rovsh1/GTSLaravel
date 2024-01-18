<?php

namespace Pkg\Hotel\Administration\Application\Service;

use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Order\Order;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Pkg\Hotel\Administration\Application\Service\StatusRules\DefaultTransitions;
use Sdk\Booking\Enum\StatusEnum;

final class EditRules
{
    protected Booking $booking;

    private Order $order;

    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
    ) {
    }

    public function booking(Booking $booking): void
    {
        $this->booking = $booking;
        $this->order = $this->orderRepository->findOrFail($booking->orderId());
    }

    public function canEditExternalNumber(): bool
    {
        return in_array($this->booking->status()->value(), [
            StatusEnum::CONFIRMED,
            StatusEnum::WAITING_CONFIRMATION,
        ]);
    }

    /**
     * @return StatusEnum[]
     */
    public function getAvailableStatusTransitions(): array
    {
        $statusTransitions = new DefaultTransitions();
        if (!$this->order->inModeration()) {
            return [];
        }

        return $statusTransitions->getAvailableTransitions($this->booking->status()->value());
    }
}