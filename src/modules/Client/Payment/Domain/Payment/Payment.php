<?php

declare(strict_types=1);

namespace Module\Client\Payment\Domain\Payment;

use DateTimeImmutable;
use Module\Client\Payment\Domain\Payment\ValueObject\InvoiceNumber;
use Module\Client\Payment\Domain\Payment\ValueObject\PaymentAmount;
use Module\Client\Payment\Domain\Payment\ValueObject\PaymentDocument;
use Module\Client\Payment\Domain\Payment\ValueObject\PaymentId;
use Module\Client\Payment\Domain\Payment\ValueObject\PaymentStatusEnum;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

final class Payment extends AbstractAggregateRoot
{
    public function __construct(
        private readonly PaymentId $id,
        private readonly ClientId $clientId,
        private PaymentStatusEnum $status,
        private readonly InvoiceNumber $invoiceNumber,
        private readonly DateTimeImmutable $issueDate,
        private readonly DateTimeImmutable $paymentDate,
        private readonly PaymentAmount $paymentAmount,
        private float $plantedSum,
        private readonly ?PaymentDocument $document,
    ) {}

    public function id(): PaymentId
    {
        return $this->id;
    }

    public function clientId(): ClientId
    {
        return $this->clientId;
    }

    public function status(): PaymentStatusEnum
    {
        return $this->status;
    }

    public function invoiceNumber(): InvoiceNumber
    {
        return $this->invoiceNumber;
    }

    public function issueDate(): DateTimeImmutable
    {
        return $this->issueDate;
    }

    public function paymentDate(): DateTimeImmutable
    {
        return $this->paymentDate;
    }

    public function paymentAmount(): PaymentAmount
    {
        return $this->paymentAmount;
    }

    public function plantedSum(): float
    {
        return $this->plantedSum;
    }

    public function document(): ?PaymentDocument
    {
        return $this->document;
    }

    public function addPlantSum(float $sum): void
    {
        $remainingSum = $this->paymentAmount->sum() - $this->plantedSum;
        if ($remainingSum < $sum) {
            throw new \RuntimeException('Insufficient funds');
        }

        $this->plantedSum += $sum;
        if (round($this->paymentAmount->sum()) === round($this->plantedSum)) {
            $this->updateStatus(PaymentStatusEnum::PAID);
        } else {
            $this->updateStatus(PaymentStatusEnum::PARTIAL_PAID);
        }
    }

    public function removePlantSum(float $sum): void
    {
        if ($this->plantedSum < $sum) {
            throw new \RuntimeException('Planted sum not enough');
        }

        //@todo нужно удалить все записи из таблицы client_payment_plants
        $this->plantedSum -= $sum;
        if ($this->plantedSum === 0.0) {
            $this->updateStatus(PaymentStatusEnum::NOT_PAID);
        }
    }

    private function updateStatus(PaymentStatusEnum $status): void
    {
        if ($status === $this->status) {
            return;
        }

        $this->status = $status;
//        $this->pushEvent(new PaymentUpdated);
    }
}
