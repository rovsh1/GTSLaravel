<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\TransferBooking\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Domain\Shared\ValueObject\BookingId;
use Module\Booking\Domain\Shared\ValueObject\BookingPrice;
use Module\Booking\Domain\Shared\ValueObject\CancelConditions;
use Module\Booking\Domain\Shared\ValueObject\CreatorId;
use Module\Booking\Domain\Shared\ValueObject\OrderId;
use Module\Booking\Infrastructure\Shared\Repository\AbstractBookingRepository as BaseRepository;
use Module\Booking\Domain\TransferBooking\TransferBooking as Entity;
use Module\Booking\Domain\TransferBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\TransferBooking\ValueObject\Details\ServiceInfo;
use Module\Booking\Infrastructure\TransferBooking\Models\Booking as Model;
use Module\Booking\Infrastructure\TransferBooking\Models\BookingDetails;
use Module\Booking\Infrastructure\TransferBooking\Models\TransferService;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class BookingRepository extends BaseRepository implements BookingRepositoryInterface
{
    protected function getModel(): string
    {
        return Model::class;
    }

    public function find(int $id): ?Entity
    {
        $booking = $this->findBase($id);
        if ($booking === null) {
            return null;
        }
        $detailsModel = BookingDetails::whereBookingId($id)->first();

        return $this->buildEntityFromModel($booking, $detailsModel);
    }

    public function findOrFail(BookingId $id): Entity
    {
        $entity = $this->find($id->value());
        if ($entity === null) {
            throw new EntityNotFoundException('Booking not found');
        }

        return $entity;
    }

    public function get(): Collection
    {
        return $this->getModel()::query()->withDetails()->get();
    }

    public function create(
        OrderId $orderId,
        CreatorId $creatorId,
        int $serviceId,
        int $cityId,
        BookingPrice $price,
        CancelConditions $cancelConditions,
        ?string $note = null
    ): Entity {
        return \DB::transaction(
            function () use (
                $orderId,
                $creatorId,
                $serviceId,
                $cityId,
                $price,
                $note,
                $cancelConditions
            ) {
                $bookingModel = $this->createBase($orderId, $price, $creatorId->value());

                $service = TransferService::find($serviceId);

                $booking = new Entity(
                    id: new BookingId($bookingModel->id),
                    orderId: new OrderId($bookingModel->order_id),
                    status: $bookingModel->status,
                    createdAt: $bookingModel->created_at->toImmutable(),
                    creatorId: $creatorId,
                    price: $price,
                    serviceInfo: new ServiceInfo(
                        $service->id,
                        $service->name,
                        $service->type,
                        $service->supplier_id,
                        $cityId,
                    ),
                    note: $note,
                    cancelConditions: $cancelConditions,
                );

                BookingDetails::create([
                    'booking_id' => $booking->id()->value(),
                    'service_id' => $service->id,
                    'city_id' => $cityId,
                    'data' => $this->serializeDetails($booking)
                ]);

                return $booking;
            }
        );
    }

    public function store(Entity $booking): bool
    {
        return \DB::transaction(function () use ($booking) {
            $base = $this->storeBase($booking);

            $details = (bool)BookingDetails::whereBookingId($booking->id()->value())
                ->update([
                    'service_id' => $booking->serviceInfo()->id(),
                    'city_id' => $booking->serviceInfo()->cityId(),
                    'data' => $this->serializeDetails($booking)
                ]);

            return $base && $details;
        });
    }

    public function delete(Entity $booking): void
    {
        $this->getModel()::query()->whereId($booking->id()->value())->update([
            'status' => $booking->status(),
            'deleted_at' => now()
        ]);
    }

    public function bulkDelete(array $ids): void
    {
        $this->getModel()::query()->whereIn('id', $ids)->delete();
    }

    public function query(): Builder
    {
        return $this->getModel()::query()->withDetails();
    }

    private function buildEntityFromModel(Model $booking, BookingDetails $detailsModel): Entity
    {
        $detailsData = $detailsModel->data;

        $cancelConditions = $detailsData['cancelConditions'] ?? null;

        return new Entity(
            id: new BookingId($booking->id),
            orderId: new OrderId($booking->order_id),
            status: $booking->status,
            createdAt: $booking->created_at->toImmutable(),
            creatorId: new CreatorId($booking->creator_id),
            price: BookingPrice::fromData($detailsData['price']),
            serviceInfo: ServiceInfo::fromData($detailsData['serviceInfo']),
            note: $detailsData['note'] ?? null,
            cancelConditions: $cancelConditions !== null
                ? CancelConditions::fromData($detailsData['cancelConditions'])
                : null,
        );
    }

    private function serializeDetails(Entity $booking): array
    {
        return [
            'note' => $booking->note(),
            'serviceInfo' => $booking->serviceInfo()->toData(),
            'price' => $booking->price()->toData(),
            'cancelConditions' => $booking->cancelConditions()?->toData(),
        ];
    }
}
