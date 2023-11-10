<?php

namespace Module\Client\Invoicing\Domain\Payment\Repository;

use Module\Client\Invoicing\Domain\Payment\Payment;
use Module\Client\Invoicing\Domain\Payment\ValueObject\InvoiceNumber;
use Module\Client\Invoicing\Domain\Payment\ValueObject\PaymentAmount;
use Module\Client\Invoicing\Domain\Payment\ValueObject\PaymentDocument;
use Module\Client\Invoicing\Domain\Payment\ValueObject\PaymentId;
use Module\Client\Invoicing\Domain\Payment\ValueObject\PaymentStatusEnum;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Sdk\Module\Support\DateTimeImmutable;

interface PaymentRepositoryInterface
{
    public function create(
        ClientId $clientId,
        PaymentStatusEnum $status,
        InvoiceNumber $invoiceNumber,
        PaymentAmount $paymentAmount,
        DateTimeImmutable $paymentDate,
        DateTimeImmutable $issuedDate,
        ?PaymentDocument $document,
    ): Payment;

    public function findOrFail(PaymentId $id): Payment;

    public function find(PaymentId $id): ?Payment;

//    public function get(InvoiceId $invoiceId): array;

    public function store(Payment $payment): void;
}
