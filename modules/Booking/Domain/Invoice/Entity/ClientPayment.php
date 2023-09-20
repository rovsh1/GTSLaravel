<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Invoice\Entity;

use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Domain\Invoice\ValueObject\ClientPaymentId;
use Module\Booking\Domain\Invoice\ValueObject\InvoiceId;
use Module\Booking\Domain\Invoice\ValueObject\PaymentDocument;
use Module\Booking\Domain\Invoice\ValueObject\InvoiceNumber;
use Module\Booking\Domain\Invoice\ValueObject\PaymentAmount;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;
use DateTimeImmutable;

final class ClientPayment extends AbstractAggregateRoot
{
    public function __construct(
        private readonly ClientPaymentId $id,
        private readonly InvoiceId $invoiceId,
        private readonly BookingId $bookingId,
        private readonly InvoiceNumber $number,
        private readonly DateTimeImmutable $issuedDate,
        private readonly DateTimeImmutable $paidDate,
        private readonly PaymentAmount $paymentAmount,
        private readonly ?PaymentDocument $document,
    ) {
    }

    public function id(): ClientPaymentId
    {
        return $this->id;
    }

    public function invoiceId(): InvoiceId
    {
        return $this->invoiceId;
    }

    public function bookingId(): BookingId
    {
        return $this->bookingId;
    }

    public function number(): InvoiceNumber
    {
        return $this->number;
    }

    public function issuedDate(): DateTimeImmutable
    {
        return $this->issuedDate;
    }

    public function paidDate(): DateTimeImmutable
    {
        return $this->paidDate;
    }

    public function paymentAmount(): PaymentAmount
    {
        return $this->paymentAmount;
    }

    public function document(): ?PaymentDocument
    {
        return $this->document;
    }
}