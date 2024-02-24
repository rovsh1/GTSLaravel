<?php

namespace Module\Supplier\Payment\Domain\Payment\Repository;

use Module\Supplier\Payment\Domain\Payment\Payment;
use Module\Supplier\Payment\Domain\Payment\ValueObject\PaymentId;
use Sdk\Booking\ValueObject\BookingId;

interface PaymentRepositoryInterface
{
    public function findOrFail(PaymentId $id): Payment;

    public function find(PaymentId $id): ?Payment;

    public function store(Payment $payment): void;

    /**
     * @param BookingId $id
     * @return Payment[]
     */
    public function findByBookingId(BookingId $id): array;
}
