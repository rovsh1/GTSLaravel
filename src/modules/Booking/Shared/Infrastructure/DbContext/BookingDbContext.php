<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\DbContext;

use Illuminate\Database\Eloquent\Builder;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\DbContext\BookingDbContextInterface;
use Module\Booking\Shared\Infrastructure\Mapper\BookingMapper;
use Module\Booking\Shared\Infrastructure\Models\Booking as BookingModel;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\BookingIdCollection;
use Sdk\Booking\ValueObject\BookingPrices;
use Sdk\Booking\ValueObject\CancelConditions;
use Sdk\Booking\ValueObject\CreatorId;
use Sdk\Booking\ValueObject\GuestId;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Module\Contracts\ContextInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;
use Sdk\Shared\Enum\ServiceTypeEnum;

class BookingDbContext implements BookingDbContextInterface
{
    public function __construct(
        private readonly BookingMapper $bookingMapper,
        private readonly ContextInterface $context,
    ) {}

    public function find(BookingId $id): ?Booking
    {
        $model = BookingModel::find($id->value());
        if ($model === null) {
            throw new EntityNotFoundException('Booking not found');
        }

        return $this->bookingMapper->fromModel($model);
    }

    public function findOrFail(BookingId $id): Booking
    {
        return $this->find($id) ?? throw new EntityNotFoundException("Booking[$id] not found");
    }

    public function getByOrderId(OrderId $orderId): array
    {
        $models = BookingModel::whereOrderId($orderId->value())->get();

        return $models->map(fn(BookingModel $booking) => $this->bookingMapper->fromModel($booking))->all();
    }

    /**
     * @param GuestId $guestId
     * @return Booking[]
     */
    public function getByGuestId(GuestId $guestId): array
    {
        $models = BookingModel::whereHasGuestId($guestId->value())->get();

        return $models->map(fn(BookingModel $booking) => $this->bookingMapper->fromModel($booking))->all();
    }

    /**
     * @param BookingIdCollection $ids
     * @return Booking[]
     */
    public function getBookings(BookingIdCollection $ids): array
    {
        $models = BookingModel::whereId($ids->map(fn(BookingId $id) => $id->value()))->get();

        return $models->map(fn(BookingModel $booking) => $this->bookingMapper->fromModel($booking))->all();
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
            'status' => $booking->status()->value(),
            'status_reason' => $booking->status()->reason(),
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

    public function touch(BookingId $id): void
    {
        BookingModel::find($id->value())->touch();
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
            'status' => StatusEnum::CREATED,
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

        return $this->bookingMapper->fromModel($model);
    }

    /**
     * @inheritDoc
     */
    public function bulkDelete(array $ids): void
    {
        BookingModel::whereIn('id', $ids)->delete();
    }
}
