<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Domain\Payment;

use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Client\Invoicing\Domain\Invoice\ValueObject\InvoiceId;
use Module\Client\Invoicing\Domain\Invoice\ValueObject\PaymentMethodEnum;
use Module\Client\Invoicing\Domain\Payment\ValueObject\InvoiceNumber;
use Module\Client\Invoicing\Domain\Payment\ValueObject\PaymentAmount;
use Module\Client\Invoicing\Domain\Payment\ValueObject\PaymentDocument;
use Module\Supplier\Moderation\Domain\Payment\ValueObject\SupplierPaymentId;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;
use Sdk\Module\Support\DateTimeImmutable;

final class SupplierPayment extends AbstractAggregateRoot
{
    public function __construct(
        private readonly SupplierPaymentId $id,
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
