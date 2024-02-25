<?php

declare(strict_types=1);

namespace Module\Supplier\Payment\Domain\Booking\Repository;

use Module\Supplier\Payment\Domain\Booking\Booking;
use Module\Supplier\Payment\Domain\Payment\ValueObject\PaymentId;
use Sdk\Booking\ValueObject\BookingId;

interface BookingRepositoryInterface
{
    public function find(BookingId $id): ?Booking;

    public function findOrFail(BookingId $id): Booking;

    public function store(Booking $booking): void;

    /**
     * @param PaymentId $paymentId
     * @return Booking[]
     */
    public function getForWaitingPayment(PaymentId $paymentId): array;

    /**
     * @param PaymentId $paymentId
     * @return Booking[]
     */
    public function getPaymentBookings(PaymentId $paymentId): array;
}
