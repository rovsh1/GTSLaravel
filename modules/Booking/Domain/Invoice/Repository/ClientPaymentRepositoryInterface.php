<?php

namespace Module\Booking\Domain\Invoice\Repository;

use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Domain\Invoice\Entity\ClientPayment;
use Module\Booking\Domain\Invoice\ValueObject\ClientPaymentId;
use Module\Booking\Domain\Invoice\ValueObject\InvoiceId;
use Module\Booking\Domain\Invoice\ValueObject\InvoiceNumber;
use Module\Booking\Domain\Invoice\ValueObject\PaymentAmount;
use Module\Booking\Domain\Invoice\ValueObject\PaymentDocument;
use Sdk\Module\Support\DateTimeImmutable;

interface ClientPaymentRepositoryInterface
{
    public function create(
        InvoiceId $invoiceId,
        BookingId $bookingId,
        InvoiceNumber $number,
        DateTimeImmutable $issuedDate,
        DateTimeImmutable $paidDate,
        PaymentAmount $paymentAmount,
        ?PaymentDocument $document,
    ): ClientPayment;

    public function find(ClientPaymentId $id): ?ClientPayment;

//    public function get(InvoiceId $invoiceId): array;

//    public function store(ClientPayment $payment): void;
}