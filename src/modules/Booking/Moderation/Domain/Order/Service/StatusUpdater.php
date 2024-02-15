<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Order\Service;

use Module\Booking\Moderation\Domain\Order\Exception\OrderHasBookingInProgress;
use Module\Booking\Moderation\Domain\Order\Exception\OrderHasNotCancelledBooking;
use Module\Booking\Moderation\Domain\Order\Exception\OrderWithoutBookings;
use Module\Booking\Moderation\Domain\Order\Exception\RefundFeeAmountBelowOrEqualZero;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Order\Order;
use Module\Booking\Shared\Domain\Shared\Exception\InvalidStatusTransition;
use Sdk\Shared\Enum\Order\OrderStatusEnum;

class StatusUpdater
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly StatusTransitionRules $statusTransitionRules,
    ) {}

    public function update(Order $order, OrderStatusEnum $status, ?float $refundFeeAmount = null): void
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
                $this->ensureAllBookingsCancelled($order);
                if ($refundFeeAmount === null && (bool)$order->clientPenalty()?->isZero()) {
                    throw new RefundFeeAmountBelowOrEqualZero();
                }
                $order->toRefundFee((float)$refundFeeAmount);
                break;
            case OrderStatusEnum::REFUND_NO_FEE:
                $this->ensureAllBookingsCancelled($order);
                $order->toRefundNoFee();
                break;
        }
    }

    private function ensureAllBookingsCancelled(Order $order): void
    {
        $bookings = $this->repository->getByOrderId($order->id());
        if (count($bookings) === 0) {
            throw new OrderWithoutBookings();
        }
        foreach ($bookings as $booking) {
            if (!$booking->isCancelled()) {
                throw new OrderHasNotCancelledBooking();
            }
        }
    }

    private function ensureAllBookingsCompleted(Order $order): void
    {
        $bookings = $this->repository->getByOrderId($order->id());
        if (count($bookings) === 0) {
            throw new OrderWithoutBookings();
        }
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
