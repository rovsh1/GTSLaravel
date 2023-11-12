<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Repository\Details;

use Module\Booking\Shared\Domain\Booking\Entity\CarRentWithDriver;
use Module\Booking\Shared\Domain\Booking\Repository\Details\CarRentWithDriverRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPeriod;
use Module\Booking\Shared\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Booking\Shared\Infrastructure\Factory\Details\CarRentWithDriverFactory;
use Module\Booking\Shared\Infrastructure\Models\Booking;
use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class CarRentWithDriverRepository extends AbstractServiceDetailsRepository implements
    CarRentWithDriverRepositoryInterface
{
    public function find(BookingId $bookingId): ?CarRentWithDriver
    {
        $booking = Booking::find($bookingId->value());
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }

        return $this->build($booking->transferDetails);
    }

    public function findOrFail(BookingId $bookingId): CarRentWithDriver
    {
        $entity = $this->find($bookingId);
        if ($entity === null) {
            throw new EntityNotFoundException('Booking details not found');
        }

        return $entity;
    }

    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        int $cityId,
        CarBidCollection $carBids,
        ?BookingPeriod $bookingPeriod,
    ): CarRentWithDriver {
        $model = Transfer::create([
            'booking_id' => $bookingId->value(),
            'date_start' => $bookingPeriod?->dateFrom(),
            'date_end' => $bookingPeriod?->dateTo(),
            'service_id' => $serviceInfo->id(),
            'data' => [
                'serviceInfo' => $this->serializeServiceInfo($serviceInfo),
                'cityId' => $cityId,
                'carBids' => $carBids->toData(),
            ]
        ]);

        return $this->build(Transfer::find($model->id));
    }

    public function store(CarRentWithDriver $details): bool
    {
        return (bool)Transfer::whereId($details->id()->value())->update([
            'date_start' => $details->bookingPeriod()?->dateFrom(),
            'date_end' => $details->bookingPeriod()?->dateTo(),
            'booking_transfer_details.data' => [
                'serviceInfo' => $this->serializeServiceInfo($details->serviceInfo()),
                'cityId' => $details->cityId()->value(),
                'carBids' => $details->carBids()->toData(),
                'meetingTablet' => $details->meetingTablet(),
                'meetingAddress' => $details->meetingAddress(),
            ]
        ]);
    }

    private function build(Transfer $details): CarRentWithDriver
    {
        return (new CarRentWithDriverFactory())->build($details);
    }
}
