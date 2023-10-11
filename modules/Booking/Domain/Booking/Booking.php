<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking;

use Module\Booking\Domain\Booking\Support\Concerns\HasStatusesTrait;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\BookingPrices;
use Module\Booking\Domain\Order\ValueObject\OrderId;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Domain\Shared\Event\BookingDeleted;
use Module\Booking\Domain\Shared\ValueObject\BookingStatusEnum;
use Module\Booking\Domain\Shared\ValueObject\CancelConditions;
use Module\Booking\Domain\Shared\ValueObject\CreatorId;
use Module\Shared\Enum\ServiceTypeEnum;
use Module\Shared\ValueObject\Timestamps;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

class Booking extends AbstractAggregateRoot implements BookingInterface
{
    use HasStatusesTrait;

    public function __construct(
        private readonly BookingId $id,
        private readonly OrderId $orderId,
        private readonly ServiceTypeEnum $serviceType,
        private BookingStatusEnum $status,
        private BookingPrices $price,
        private ?CancelConditions $cancelConditions,
        private ?string $note,
        private readonly CreatorId $creatorId,
        private readonly Timestamps $timestamps,
    ) {
    }

    public function id(): BookingId
    {
        return $this->id;
    }

    public function orderId(): OrderId
    {
        return $this->orderId;
    }

    public function serviceType(): ServiceTypeEnum
    {
        return $this->serviceType;
    }

    public function prices(): BookingPrices
    {
        return $this->price;
    }

    public function updatePrice(BookingPrices $price): void
    {
        if (!$price->isEqual($this->price)) {
            return;
        }

        $priceBefore = $this->price;
        $this->price = $price;
        $this->pushEvent(new BookingPriceChanged($this, $priceBefore));
    }

    public function creatorId(): CreatorId
    {
        return $this->creatorId;
    }

    public function timestamps(): Timestamps
    {
        return $this->timestamps;
    }

    public function delete(): void
    {
        $this->setStatus(BookingStatusEnum::DELETED);
        $this->pushEvent(
            new BookingDeleted($this)
        );
    }

    public function note(): ?string
    {
        return $this->note;
    }

    public function setNote(string|null $note): void
    {
        $this->note = $note;
    }

    public function cancelConditions(): ?CancelConditions
    {
        return $this->cancelConditions;
    }

    public function setCancelConditions(?CancelConditions $cancelConditions): void
    {
        $this->cancelConditions = $cancelConditions;
    }
}
