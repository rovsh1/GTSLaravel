<?php

declare(strict_types=1);

namespace Module\Supplier\Payment\Domain\Booking;

use Sdk\Booking\Enum\StatusEnum;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\SupplierId;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;
use Sdk\Shared\ValueObject\Money;

final class Booking extends AbstractAggregateRoot
{
    public function __construct(
        private readonly BookingId $id,
        private readonly SupplierId $supplierId,
        private readonly Money $supplierPrice,
        private readonly ?Money $supplierPenalty,
        private readonly Money $payedAmount,
        private StatusEnum $status,
    ) {}

    public function id(): BookingId
    {
        return $this->id;
    }

    public function supplierId(): SupplierId
    {
        return $this->supplierId;
    }

    public function status(): StatusEnum
    {
        return $this->status;
    }

    public function supplierPrice(): Money
    {
        return $this->supplierPrice;
    }

    public function supplierPenalty(): ?Money
    {
        return $this->supplierPenalty;
    }

    public function payedAmount(): Money
    {
        return $this->payedAmount;
    }
}
