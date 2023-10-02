<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Infrastructure\Repository;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Airport\Domain\Booking\Booking as Entity;
use Module\Booking\Airport\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Airport\Domain\Booking\ValueObject\Details\AdditionalInfo;
use Module\Booking\Airport\Domain\Booking\ValueObject\Details\AirportInfo;
use Module\Booking\Airport\Domain\Booking\ValueObject\Details\ServiceInfo;
use Module\Booking\Airport\Infrastructure\Models\Airport;
use Module\Booking\Airport\Infrastructure\Models\AirportService;
use Module\Booking\Airport\Infrastructure\Models\Booking as Model;
use Module\Booking\Airport\Infrastructure\Models\BookingDetails;
use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Common\Domain\ValueObject\BookingPrice;
use Module\Booking\Common\Domain\ValueObject\CancelConditions;
use Module\Booking\Common\Domain\ValueObject\CreatorId;
use Module\Booking\Common\Domain\ValueObject\OrderId;
use Module\Booking\Common\Infrastructure\Repository\AbstractBookingRepository as BaseRepository;
use Module\Booking\Order\Domain\ValueObject\GuestIdsCollection;
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
        int $airportId,
        CarbonInterface $date,
        BookingPrice $price,
        AdditionalInfo $additionalInfo,
        CancelConditions $cancelConditions,
        ?string $note = null
    ): Entity {
        return \DB::transaction(
            function () use (
                $orderId,
                $creatorId,
                $serviceId,
                $airportId,
                $date,
                $price,
                $note,
                $additionalInfo,
                $cancelConditions
            ) {
                $bookingModel = $this->createBase($orderId, $price, $creatorId->value());

                $airport = Airport::find($airportId);
                $service = AirportService::find($serviceId);

                $booking = new Entity(
                    id: new BookingId($bookingModel->id),
                    orderId: new OrderId($bookingModel->order_id),
                    status: $bookingModel->status,
                    createdAt: $bookingModel->created_at->toImmutable(),
                    creatorId: $creatorId,
                    price: $price,
                    note: $note,
                    airportInfo: new AirportInfo(
                        $airport->id,
                        $airport->name
                    ),
                    guestIds: new GuestIdsCollection(),
                    date: $date->toImmutable(),
                    serviceInfo: new ServiceInfo(
                        $service->id,
                        $service->name,
                        $service->type,
                        $service->supplier_id,
                    ),
                    cancelConditions: $cancelConditions,
                    additionalInfo: $additionalInfo,
                );

                BookingDetails::create([
                    'booking_id' => $booking->id()->value(),
                    'airport_id' => $airport->id,
                    'service_id' => $service->id,
                    'date' => $date,
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
                    'airport_id' => $booking->airportInfo()->id(),
                    'date' => $booking->date(),
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
            note: $detailsData['note'] ?? null,
            price: BookingPrice::fromData($detailsData['price']),
            guestIds: GuestIdsCollection::fromData($booking->guest_ids),
            airportInfo: AirportInfo::fromData($detailsData['airportInfo']),
            serviceInfo: ServiceInfo::fromData($detailsData['serviceInfo']),
            date: CarbonImmutable::createFromTimestamp($detailsData['date']),
            additionalInfo: AdditionalInfo::fromData($detailsData['additionalInfo']),
            cancelConditions: $cancelConditions !== null
                ? CancelConditions::fromData($detailsData['cancelConditions'])
                : null,
        );
    }

    private function serializeDetails(Entity $booking): array
    {
        return [
            'note' => $booking->note(),
            'airportInfo' => $booking->airportInfo()->toData(),
            'serviceInfo' => $booking->serviceInfo()->toData(),
            'date' => $booking->date()->getTimestamp(),
            'price' => $booking->price()->toData(),
            'additionalInfo' => $booking->additionalInfo()->toData(),
            'cancelConditions' => $booking->cancelConditions()?->toData(),
        ];
    }
}
