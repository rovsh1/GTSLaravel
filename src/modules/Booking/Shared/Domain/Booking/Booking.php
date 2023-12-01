<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking;

use Module\Booking\Shared\Domain\Booking\Event\BookingDeleted;
use Module\Booking\Shared\Domain\Booking\Event\NoteChanged;
use Module\Booking\Shared\Domain\Booking\Event\PriceUpdated;
use Module\Booking\Shared\Domain\Booking\Support\Concerns\HasStatusesTrait;
use Module\Booking\Shared\Domain\Booking\Support\Concerns\StatusesFlagsTrait;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\BookingPrices;
use Sdk\Booking\ValueObject\CancelConditions;
use Sdk\Booking\ValueObject\Context;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;
use Sdk\Shared\Enum\Booking\BookingStatusEnum;
use Sdk\Shared\Enum\ServiceTypeEnum;
use Sdk\Shared\ValueObject\Timestamps;

class Booking extends AbstractAggregateRoot
{
    use HasStatusesTrait;
    use StatusesFlagsTrait;

    public function __construct(
        private readonly BookingId $id,
        private readonly OrderId $orderId,
        private readonly ServiceTypeEnum $serviceType,
        private BookingStatusEnum $status,
        private BookingPrices $prices,
        private ?CancelConditions $cancelConditions,
        private ?string $note,
        private readonly Context $context,
        private readonly Timestamps $timestamps,
    ) {}

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
        return $this->prices;
    }

    public function updatePrice(BookingPrices $price): void
    {
        if ($price->isEqual($this->prices)) {
            return;
        }

        $priceBefore = $this->prices;
        $this->prices = $price;
        $this->pushEvent(new PriceUpdated($this, $priceBefore));
    }

    public function context(): Context
    {
        return $this->context;
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
        $this->pushEvent(new NoteChanged($this, $this->note));
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

    public function serialize(): array
    {
        return [
            'id' => $this->id->value(),
            'orderId' => $this->orderId->value(),
            'serviceType' => $this->serviceType->value,
            'status' => $this->status->value,
            'prices' => $this->prices->serialize(),
            'cancelConditions' => $this->cancelConditions?->serialize(),
            'note' => $this->note,
            'context' => $this->context->serialize(),
            'timestamps' => $this->timestamps->serialize(),
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new static(
            id: new BookingId($payload['id']),
            orderId: new OrderId($payload['orderId']),
            serviceType: ServiceTypeEnum::from($payload['serviceType']),
            status: BookingStatusEnum::from($payload['status']),
            prices: BookingPrices::deserialize($payload['prices']),
            cancelConditions: $payload['cancelConditions'] ? CancelConditions::deserialize(
                $payload['cancelConditions']
            ) : null,
            note: $payload['note'],
            context: Context::deserialize($payload['context']),
            timestamps: Timestamps::deserialize($payload['timestamps'])
        );
    }
}
