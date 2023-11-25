<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Repository;

use Illuminate\Database\Eloquent\Builder;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPriceItem;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPrices;
use Module\Booking\Shared\Domain\Booking\ValueObject\Context;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;
use Module\Booking\Shared\Domain\Shared\ValueObject\CancelConditions;
use Module\Booking\Shared\Domain\Shared\ValueObject\CreatorId;
use Module\Booking\Shared\Infrastructure\Models\Booking as BookingModel;
use Module\Shared\Contracts\Service\ApplicationContextInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;
use Sdk\Shared\Enum\Booking\BookingStatusEnum;
use Sdk\Shared\Enum\ServiceTypeEnum;
use Sdk\Shared\Support\RepositoryInstances;
use Sdk\Shared\ValueObject\Timestamps;

class BookingRepository implements BookingRepositoryInterface
{
    private RepositoryInstances $instances;

    public function __construct(
        private readonly ApplicationContextInterface $context
    )
    {
        $this->instances = new RepositoryInstances();
    }

    public function add(Booking $booking): void
    {
        $this->instances->add($booking->id(), $booking);
    }

    public function get(): array
    {
        return $this->instances->all();
    }

    public function find(BookingId $id): ?Booking
    {
        if ($this->instances->has($id)) {
            return $this->instances->get($id);
        }

        $model = BookingModel::find($id->value());
        if ($model === null) {
            throw new EntityNotFoundException('Booking not found');
        }

        return $this->buildEntityFromModel($model);
    }

    public function getByOrderId(OrderId $orderId): array
    {
        $models = BookingModel::whereOrderId($orderId->value())->get();

        return $models->map(fn(BookingModel $booking) => $this->instances->get($booking->id)
            ?? $this->buildEntityFromModel($booking)
        )->all();
    }

    public function findOrFail(BookingId $id): Booking
    {
        return $this->find($id) ?? throw new EntityNotFoundException("Booking[$id] not found");
    }

    public function query(): Builder
    {
        return BookingModel::query();
    }

    public function delete(Booking $booking): void
    {
        BookingModel::whereId($booking->id()->value())->delete();
    }

    public function store(Booking $booking): void
    {
        $clientPrice = $booking->prices()->clientPrice();
        $supplierPrice = $booking->prices()->supplierPrice();
        BookingModel::whereId($booking->id()->value())->update([
            'status' => $booking->status(),
            'note' => $booking->note(),
            'client_price' => $clientPrice->calculatedValue(),
            'client_manual_price' => $clientPrice->manualValue(),
            'client_currency' => $clientPrice->currency(),
            'client_penalty' => $clientPrice->penaltyValue(),
            'supplier_price' => $supplierPrice->calculatedValue(),
            'supplier_manual_price' => $supplierPrice->manualValue(),
            'supplier_currency' => $supplierPrice->currency(),
            'supplier_penalty' => $supplierPrice->penaltyValue(),
            'cancel_conditions' => $booking->cancelConditions()?->serialize()
        ]);
    }

    public function create(
        OrderId $orderId,
        CreatorId $creatorId,
        BookingPrices $prices,
        ?CancelConditions $cancelConditions,
        ServiceTypeEnum $serviceType,
        ?string $note = null,
    ): Booking {
        $clientPrice = $prices->clientPrice();
        $supplierPrice = $prices->supplierPrice();

        $model = BookingModel::create([
            'order_id' => $orderId->value(),
            'service_type' => $serviceType,
            'status' => BookingStatusEnum::CREATED,
            'source' => $this->context->source(),
            'creator_id' => $creatorId->value(),
            'client_price' => $clientPrice->calculatedValue(),
            'client_manual_price' => $clientPrice->manualValue(),
            'client_currency' => $clientPrice->currency(),
            'client_penalty' => $clientPrice->penaltyValue(),
            'supplier_price' => $supplierPrice->calculatedValue(),
            'supplier_manual_price' => $supplierPrice->manualValue(),
            'supplier_currency' => $supplierPrice->currency(),
            'supplier_penalty' => $supplierPrice->penaltyValue(),
            'cancel_conditions' => $cancelConditions?->serialize(),
            'note' => $note
        ]);

        return $this->buildEntityFromModel($model);
    }

    /**
     * @inheritDoc
     */
    public function bulkDelete(array $ids): void
    {
        BookingModel::whereIn('id', $ids)->delete();
    }

    private function buildEntityFromModel(BookingModel $booking): Booking
    {
        $instance = new Booking(
            id: new BookingId($booking->id),
            orderId: new OrderId($booking->order_id),
            serviceType: $booking->service_type,
            status: $booking->status,
            prices: $this->buildBookingPrices($booking),
            cancelConditions: $booking->cancel_conditions !== null
                ? CancelConditions::deserialize($booking->cancel_conditions)
                : null,
            note: $booking->note,
            context: new Context(
                source: $booking->source,
                creatorId: new CreatorId($booking->creator_id),
            ),
            timestamps: new Timestamps(
                $booking->created_at->toImmutable(),
                $booking->updated_at->toImmutable(),
            ),
        );

        $this->instances->add($instance->id(), $instance);

        return $instance;
    }

    private function buildBookingPrices(BookingModel $booking): BookingPrices
    {
        return new BookingPrices(
            clientPrice: new BookingPriceItem(
                currency: $booking->client_currency,
                calculatedValue: $booking->client_price,
                manualValue: $booking->client_manual_price,
                penaltyValue: $booking->client_penalty,
            ),
            supplierPrice: new BookingPriceItem(
                currency: $booking->supplier_currency,
                calculatedValue: $booking->supplier_price,
                manualValue: $booking->supplier_manual_price,
                penaltyValue: $booking->supplier_penalty,
            ),
        );
    }
}
