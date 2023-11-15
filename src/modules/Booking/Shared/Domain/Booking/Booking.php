<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking;

use Module\Booking\Shared\Domain\Booking\Event\BookingDeleted;
use Module\Booking\Shared\Domain\Booking\Support\Concerns\HasStatusesTrait;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPrices;
use Module\Booking\Shared\Domain\Booking\ValueObject\Context;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;
use Module\Booking\Shared\Domain\Shared\ValueObject\CancelConditions;
use Module\Shared\Contracts\Support\SerializableDataInterface;
use Module\Shared\Enum\Booking\BookingStatusEnum;
use Module\Shared\Enum\ServiceTypeEnum;
use Module\Shared\ValueObject\Timestamps;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

class Booking extends AbstractAggregateRoot implements SerializableDataInterface
{
    use HasStatusesTrait;

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
        //@todo нужен ли ивент?
//        $this->pushEvent(new BookingPriceChanged($this, $priceBefore));
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

    public function isConfirmed(): bool
    {
        return $this->status === BookingStatusEnum::CONFIRMED;
    }

    public function isCancelled(): bool
    {
        return in_array($this->status, [
            BookingStatusEnum::CANCELLED,
            BookingStatusEnum::CANCELLED_FEE,
            BookingStatusEnum::CANCELLED_NO_FEE,
            BookingStatusEnum::DELETED,
            BookingStatusEnum::WAITING_CANCELLATION,//@todo должно ли быть тут?
        ]);
    }

    public function toData(): array
    {
        return [
            'id' => $this->id->value(),
            'orderId' => $this->orderId->value(),
            'serviceType' => $this->serviceType->value,
            'status' => $this->status->value,
            'prices' => $this->prices->toData(),
            'cancelConditions' => $this->cancelConditions?->toData(),
            'note' => $this->note,
            'context' => $this->context->toData(),
            'timestamps' => $this->timestamps->toData(),
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            id: new BookingId($data['id']),
            orderId: new OrderId($data['orderId']),
            serviceType: ServiceTypeEnum::from($data['serviceType']),
            status: BookingStatusEnum::from($data['status']),
            prices: BookingPrices::fromData($data['prices']),
            cancelConditions: $data['cancelConditions'] ? CancelConditions::fromData($data['cancelConditions']) : null,
            note: $data['note'],
            context: Context::fromData($data['context']),
            timestamps: Timestamps::fromData($data['timestamps'])
        );
    }
}
