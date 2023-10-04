<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\AirportBooking\Repository;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Domain\AirportBooking\AirportBooking as Entity;
use Module\Booking\Domain\AirportBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\AirportBooking\ValueObject\Details\AdditionalInfo;
use Module\Booking\Domain\AirportBooking\ValueObject\Details\AirportInfo;
use Module\Booking\Domain\AirportBooking\ValueObject\Details\ServiceInfo;
use Module\Booking\Infrastructure\AirportBooking\Models\Airport;
use Module\Booking\Infrastructure\AirportBooking\Models\AirportService;
use Module\Booking\Infrastructure\AirportBooking\Models\Booking as Model;
use Module\Booking\Infrastructure\AirportBooking\Models\BookingDetails;
use Module\Booking\Domain\Order\ValueObject\GuestIdsCollection;
use Module\Booking\Domain\Shared\ValueObject\BookingId;
use Module\Booking\Domain\Shared\ValueObject\BookingPrice;
use Module\Booking\Domain\Shared\ValueObject\CancelConditions;
use Module\Booking\Domain\Shared\ValueObject\CreatorId;
use Module\Booking\Domain\Shared\ValueObject\OrderId;
use Module\Booking\Infrastructure\Shared\Repository\AbstractBookingRepository as BaseRepository;
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
                    serviceInfo: new ServiceInfo(
                        $service->id,
                        $service->name,
                        $service->type,
                        $service->supplier_id,
                    ),
                    airportInfo: new AirportInfo(
                        $airport->id,
                        $airport->name
                    ),
                    date: $date->toImmutable(),
                    cancelConditions: $cancelConditions,
                    additionalInfo: $additionalInfo,
                    guestIds: new GuestIdsCollection(),
                    note: $note,
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
            price: BookingPrice::fromData($detailsData['price']),
            serviceInfo: ServiceInfo::fromData($detailsData['serviceInfo']),
            airportInfo: AirportInfo::fromData($detailsData['airportInfo']),
            date: CarbonImmutable::createFromTimestamp($detailsData['date']),
            cancelConditions: $cancelConditions !== null
                ? CancelConditions::fromData($detailsData['cancelConditions'])
                : null,
            additionalInfo: AdditionalInfo::fromData($detailsData['additionalInfo']),
            guestIds: GuestIdsCollection::fromData($booking->guest_ids),
            note: $detailsData['note'] ?? null,
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
