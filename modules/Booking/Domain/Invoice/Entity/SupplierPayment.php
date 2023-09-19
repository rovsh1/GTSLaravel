<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Invoice\Entity;

use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Domain\Invoice\ValueObject\SupplierPaymentId;
use Module\Booking\Domain\Invoice\ValueObject\InvoiceId;
use Module\Booking\Domain\Invoice\ValueObject\PaymentDocument;
use Module\Booking\Domain\Invoice\ValueObject\InvoiceNumber;
use Module\Booking\Domain\Invoice\ValueObject\PaymentAmount;
use Module\Booking\Domain\Invoice\ValueObject\PaymentMethodEnum;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;
use Sdk\Module\Support\DateTimeImmutable;

final class SupplierPayment extends AbstractAggregateRoot
{
    public function __construct(
        private readonly SupplierPaymentId $id,
        private readonly InvoiceId $invoiceId,
        private readonly BookingId $bookingId,
        private readonly InvoiceNumber $number,
        private readonly DateTimeImmutable $issuedDate,
        private readonly DateTimeImmutable $paidDate,
        private readonly PaymentAmount $paymentAmount,
        private readonly PaymentMethodEnum $paymentMethod,
        private readonly ?PaymentDocument $document,
    ) {
    }

    public function id(): SupplierPaymentId
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

    public function paymentMethod(): PaymentMethodEnum
    {
        return $this->paymentMethod;
    }

    public function document(): ?PaymentDocument
    {
        return $this->document;
    }
}
