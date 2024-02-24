<?php

declare(strict_types=1);

namespace Module\Supplier\Payment\Domain\Payment;

use DateTimeImmutable;
use Module\Supplier\Payment\Domain\Payment\ValueObject\PaymentId;
use Module\Supplier\Payment\Domain\Payment\Event\PaymentLandingsModified;
use Module\Supplier\Payment\Domain\Payment\Event\PaymentModified;
use Module\Supplier\Payment\Domain\Payment\Exception\InvalidLandingSumDecimals;
use Module\Supplier\Payment\Domain\Payment\Exception\PaymentInsufficientFunds;
use Module\Supplier\Payment\Domain\Payment\ValueObject\InvoiceNumber;
use Module\Supplier\Payment\Domain\Payment\ValueObject\LandingCollection;
use Module\Supplier\Payment\Domain\Payment\ValueObject\PaymentDocument;
use Sdk\Booking\ValueObject\SupplierId;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;
use Sdk\Shared\Enum\PaymentStatusEnum;
use Sdk\Shared\ValueObject\Money;

final class Payment extends AbstractAggregateRoot
{
    public function __construct(
        private readonly PaymentId $id,
        private readonly SupplierId $supplierId,
        private PaymentStatusEnum $status,
        private readonly InvoiceNumber $invoiceNumber,
        private readonly DateTimeImmutable $issueDate,
        private readonly DateTimeImmutable $paymentDate,
        private readonly Money $paymentAmount,
        private LandingCollection $landings,
        private readonly ?PaymentDocument $document,
    ) {}

    public function id(): PaymentId
    {
        return $this->id;
    }

    public function supplierId(): SupplierId
    {
        return $this->supplierId;
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

    public function paymentAmount(): Money
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

    /**
     * @param LandingCollection $landings
     * @return void
     * @throws PaymentInsufficientFunds
     * @throws InvalidLandingSumDecimals
     */
    public function setLandings(LandingCollection $landings): void
    {
        if ($this->landings->isEqual($landings)) {
            return;
        }

        $paymentCurrencyDecimalsCount = Money::getDecimalsCount($this->paymentAmount->currency());
        foreach ($landings as $landing) {
            $decimalsCount = Money::countDecimals($landing->sum());
            if ($decimalsCount > $paymentCurrencyDecimalsCount) {
                throw new InvalidLandingSumDecimals('Invalid landing decimals');
            }
        }

        $landingsSum = $landings->sum();
        $remainingSum = $this->paymentAmount->value() - $landingsSum;
        if ($remainingSum < 0) {
            throw new PaymentInsufficientFunds();
        }

        $oldLandings = $this->landings;
        $this->landings = $landings;
        $this->pushEvent(new PaymentLandingsModified($this, $landings, $oldLandings));

        switch (true) {
            case $landingsSum === 0.0:
                $this->updateStatus(PaymentStatusEnum::NOT_PAID);
                break;
            case $this->paymentAmount->value() === $landingsSum:
                $this->updateStatus(PaymentStatusEnum::PAID);
                break;
            default:
                $this->updateStatus(PaymentStatusEnum::PARTIAL_PAID);
                break;
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
