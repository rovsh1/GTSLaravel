<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Repository\Details;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Entity\TransferToRailway;
use Module\Booking\Shared\Domain\Booking\Repository\Details\TransferToRailwayRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Booking\Shared\Infrastructure\Factory\Details\TransferToRailwayFactory;
use Module\Booking\Shared\Infrastructure\Models\Booking;
use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class TransferToRailwayRepository extends AbstractServiceDetailsRepository implements
    TransferToRailwayRepositoryInterface
{
    public function find(BookingId $bookingId): TransferToRailway
    {
        $booking = Booking::find($bookingId->value());
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }

        return $this->build($booking->transferDetails);
    }

    public function findOrFail(BookingId $bookingId): TransferToRailway
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
        int $railwayStationId,
        int $cityId,
        CarBidCollection $carBids,
        ?string $trainNumber,
        ?string $meetingTablet,
        ?DateTimeInterface $departureDate,
    ): TransferToRailway {
        $model = Transfer::create([
            'booking_id' => $bookingId->value(),
            'date_start' => $departureDate,
            'service_id' => $serviceInfo->id(),
            'data' => [
                'serviceInfo' => $this->serializeServiceInfo($serviceInfo),
                'railwayStationId' => $railwayStationId,
                'cityId' => $cityId,
                'trainNumber' => $trainNumber,
                'meetingTablet' => $meetingTablet,
                'carBids' => $carBids->toData(),
            ]
        ]);

        return $this->build(Transfer::find($model->id));
    }

    public function store(TransferToRailway $details): bool
    {
        return (bool)Transfer::whereId($details->id()->value())->update([
            'date_start' => $details->departureDate(),
            'booking_transfer_details.data' => [
                'serviceInfo' => $this->serializeServiceInfo($details->serviceInfo()),
                'railwayStationId' => $details->railwayStationId()->value(),
                'trainNumber' => $details->trainNumber(),
                'meetingTablet' => $details->meetingTablet(),
                'carBids' => $details->carBids()->toData(),
            ]
        ]);
    }

    private function build(Transfer $details): TransferToRailway
    {
        return (new TransferToRailwayFactory())->build($details);
    }
}
