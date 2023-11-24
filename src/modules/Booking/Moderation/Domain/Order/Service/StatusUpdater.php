<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Order\Service;

use Module\Booking\Moderation\Domain\Order\Adapter\InvoiceAdapterInterface;
use Module\Booking\Moderation\Domain\Order\Exception\OrderHasBookingInProgress;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Order\Order;

class StatusUpdater
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly InvoiceAdapterInterface $invoiceAdapter
    ) {
    }

    public function toInProgress(Order $order): void
    {
        $order->toInProgress();
    }

    public function toWaitingInvoice(Order $order): void
    {
        $bookings = $this->repository->getByOrderId($order->id());
        foreach ($bookings as $booking) {
            if ($booking->inModeration()) {
                throw new OrderHasBookingInProgress();
            }
        }
        $order->toWaitingInvoice();
    }

    public function toInvoiced(Order $order): void
    {
        $order->toInvoiced();
    }

    public function toPartialPaid(Order $order): void
    {
        $order->toPartialPaid();
    }

    public function toPaid(Order $order): void
    {
        $order->toPaid();
    }

    public function cancel(Order $order): void
    {
        $this->invoiceAdapter->cancelInvoice($order->id());
        $order->cancel();
    }

    public function toRefundFee(Order $order): void
    {
        $order->toRefundFee();
    }

    public function toRefundNoFee(Order $order): void
    {
        $order->toRefundNoFee();
    }
}
