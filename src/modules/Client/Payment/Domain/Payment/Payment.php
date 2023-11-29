<?php

declare(strict_types=1);

namespace Module\Client\Payment\Domain\Payment;

use DateTimeImmutable;
use Module\Client\Payment\Domain\Payment\Event\PaymentLandingsModified;
use Module\Client\Payment\Domain\Payment\Event\PaymentModified;
use Module\Client\Payment\Domain\Payment\Exception\PaymentInsufficientFunds;
use Module\Client\Payment\Domain\Payment\ValueObject\InvoiceNumber;
use Module\Client\Payment\Domain\Payment\ValueObject\LandingCollection;
use Module\Client\Payment\Domain\Payment\ValueObject\PaymentAmount;
use Module\Client\Payment\Domain\Payment\ValueObject\PaymentDocument;
use Module\Client\Payment\Domain\Payment\ValueObject\PaymentStatusEnum;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Module\Client\Shared\Domain\ValueObject\PaymentId;
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
        private LandingCollection $landings,
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

    public function lendedSum(): float
    {
        return $this->landings->sum();
    }

    public function document(): ?PaymentDocument
    {
        return $this->document;
    }

    public function landings(): LandingCollection
    {
        return $this->landings;
    }

    public function setLandings(LandingCollection $landings): void
    {
        if ($this->landings->isEqual($landings)) {
            return;
        }

        $landingsSum = (int)$landings->sum();
        $remainingSum = (int)$this->paymentAmount->sum() - $landingsSum;
        if ($remainingSum < 0) {
            throw new PaymentInsufficientFunds();
        }

        $oldLandings = $this->landings;
        $this->landings = $landings;
        $this->pushEvent(new PaymentLandingsModified($this, $landings, $oldLandings));

        $roundedLandingsSum = $landingsSum;
        if ($roundedLandingsSum === 0) {
            $this->updateStatus(PaymentStatusEnum::NOT_PAID);
            return;
        }

        $roundedPaymentAmount = (int)$this->paymentAmount->sum();
        if ($roundedPaymentAmount === $roundedLandingsSum) {
            $this->updateStatus(PaymentStatusEnum::PAID);
        } else {
            $this->updateStatus(PaymentStatusEnum::PARTIAL_PAID);
        }
    }

    private function updateStatus(PaymentStatusEnum $status): void
    {
        if ($status === $this->status) {
            return;
        }

        $this->status = $status;
        $this->pushEvent(new PaymentModified($this));
    }
}
