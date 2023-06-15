<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Infrastructure\Repository;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Airport\Domain\Entity\Airport;
use Module\Booking\Airport\Domain\Entity\Booking as Entity;
use Module\Booking\Airport\Domain\Entity\Service;
use Module\Booking\Airport\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Airport\Domain\ValueObject\Details\AirportInfo;
use Module\Booking\Airport\Domain\ValueObject\Details\ServiceInfo;
use Module\Booking\Airport\Infrastructure\Models\Booking as Model;
use Module\Booking\Airport\Infrastructure\Models\BookingDetails;
use Module\Booking\Common\Infrastructure\Repository\AbstractBookingRepository as BaseRepository;
use Module\Shared\Domain\ValueObject\Id;

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

    public function get(): Collection
    {
        return $this->getModel()::query()->withDetails()->get();
    }

    public function create(
        int $orderId,
        int $creatorId,
        Service $service,
        Airport $airport,
        CarbonInterface $date,
        ?string $note = null
    ): Entity {
        return \DB::transaction(
            function () use ($orderId, $creatorId, $service, $airport, $date, $note) {
                $bookingModel = $this->createBase($orderId, $creatorId);

                $booking = new Entity(
                    id: new Id($bookingModel->id),
                    orderId: new Id($bookingModel->order_id),
                    status: $bookingModel->status,
                    type: $bookingModel->type,
                    createdAt: $bookingModel->created_at->toImmutable(),
                    creatorId: new Id($bookingModel->creator_id),
                    note: $note,
                    airportInfo: new AirportInfo(
                        $airport->id()->value(),
                        $airport->name()
                    ),
                    date: $date->toImmutable(),
                    serviceInfo: new ServiceInfo(
                        $service->id()->value(),
                        $service->name(),
                        $service->type()
                    ),
                );

                BookingDetails::create([
                    'booking_id' => $booking->id()->value(),
                    'airport_id' => $airport->id()->value(),
                    'service_id' => $service->id()->value(),
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

    private function buildEntityFromModel(Model $booking, BookingDetails $detailsModel): Entity
    {
        $detailsData = $detailsModel->data;

        return new Entity(
            id: new Id($booking->id),
            orderId: new Id($booking->order_id),
            status: $booking->status,
            type: $booking->type,
            createdAt: $booking->created_at->toImmutable(),
            creatorId: new Id($booking->creator_id),
            note: $detailsData['note'] ?? null,
            airportInfo: AirportInfo::fromData($detailsData['airportInfo']),
            serviceInfo: ServiceInfo::fromData($detailsData['serviceInfo']),
            date: CarbonImmutable::createFromFormat('U', $detailsData['date'])
        );
    }

    private function serializeDetails(Entity $booking): array
    {
        return [
            'note' => $booking->note(),
            'airportInfo' => $booking->airportInfo()->toData(),
            'serviceInfo' => $booking->serviceInfo()->toData(),
            'date' => $booking->date()->getTimestamp(),
        ];
    }
}
