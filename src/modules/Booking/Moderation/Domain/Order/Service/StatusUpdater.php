<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Order\Service;

use Module\Booking\Moderation\Domain\Order\Exception\OrderHasBookingInProgress;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Order\Order;
use Sdk\Shared\Enum\Order\OrderStatusEnum;

class StatusUpdater
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly StatusTransitionRules $statusTransitionRules,
    ) {
    }

    public function update(Order $order, OrderStatusEnum $status): void
    {
        $this->ensureCanTransitToStatus($order, $status);

        switch ($status) {
            case OrderStatusEnum::IN_PROGRESS:
                $order->toInProgress();
                break;
            case OrderStatusEnum::WAITING_INVOICE:
                $this->ensureAllBookingsCompleted($order);
                $order->toWaitingInvoice();
                break;
            case OrderStatusEnum::INVOICED:
                $order->toInvoiced();
                break;
            case OrderStatusEnum::PARTIAL_PAID:
                $order->toPartialPaid();
                break;
            case OrderStatusEnum::PAID:
                $order->toPaid();
                break;
            case OrderStatusEnum::CANCELLED:
                $order->cancel();
                break;
            case OrderStatusEnum::REFUND_FEE:
                $order->toRefundFee();
                break;
            case OrderStatusEnum::REFUND_NO_FEE:
                $order->toRefundNoFee();
                break;
        }
    }

    private function ensureAllBookingsCompleted(Order $order): void
    {
        $bookings = $this->repository->getByOrderId($order->id());
        foreach ($bookings as $booking) {
            if ($booking->inModeration()) {
                throw new OrderHasBookingInProgress();
            }
        }
    }

    private function ensureCanTransitToStatus(Order $order, OrderStatusEnum $statusTo): void
    {
        if (!$this->statusTransitionRules->canTransit($order->status(), $statusTo)) {
            throw new InvalidStatusTransition("Can't change status for booking [{$order->id()->value()}]]");
        }
    }
}
