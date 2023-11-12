<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Repository\Details;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Entity\DayCarTrip;
use Module\Booking\Shared\Domain\Booking\Repository\Details\DayCarTripRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Booking\Shared\Infrastructure\Factory\Details\DayCarTripFactory;
use Module\Booking\Shared\Infrastructure\Models\Booking;
use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class DayCarTripRepository extends AbstractServiceDetailsRepository implements DayCarTripRepositoryInterface
{
    public function find(BookingId $bookingId): ?DayCarTrip
    {
        $booking = Booking::find($bookingId->value());
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }

        return $this->build($booking->transferDetails);
    }

    public function findOrFail(BookingId $bookingId): DayCarTrip
    {
        $model = $this->find($bookingId);
        if ($model === null) {
            throw new EntityNotFoundException('Booking details not found');
        }

        return $model;
    }

    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        int $cityId,
        CarBidCollection $carBids,
        ?string $destinationsDescription,
        ?DateTimeInterface $departureDate,
    ): DayCarTrip {
        $model = Transfer::create([
            'booking_id' => $bookingId->value(),
            'date_start' => $departureDate,
            'service_id' => $serviceInfo->id(),
            'data' => [
                'serviceInfo' => $this->serializeServiceInfo($serviceInfo),
                'carBids' => $carBids->toData(),
                'cityId' => $cityId,
                'destinationsDescription' => $destinationsDescription,
            ]
        ]);

        return $this->build(Transfer::find($model->id));
    }

    public function store(DayCarTrip $details): bool
    {
        return (bool)Transfer::whereId($details->id()->value())->update([
            'date_start' => $details->departureDate(),
            'booking_transfer_details.data' => [
                'serviceInfo' => $this->serializeServiceInfo($details->serviceInfo()),
                'cityId' => $details->cityId()->value(),
                'carBids' => $details->carBids()->toData(),
                'destinationsDescription' => $details->destinationsDescription(),
            ]
        ]);
    }

    private function build(Transfer $details): DayCarTrip
    {
        return (new DayCarTripFactory())->build($details);
    }
}
