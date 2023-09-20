<?php

namespace Module\Client\Domain\Payment\Repository;

use Module\Client\Domain\Payment\Payment;
use Module\Client\Domain\Payment\ValueObject\InvoiceNumber;
use Module\Client\Domain\Payment\ValueObject\PaymentAmount;
use Module\Client\Domain\Payment\ValueObject\PaymentDocument;
use Module\Client\Domain\Payment\ValueObject\PaymentId;
use Module\Client\Domain\Payment\ValueObject\PaymentStatusEnum;
use Module\Client\Domain\Shared\ValueObject\ClientId;
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