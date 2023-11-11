<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Repository\Details;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Entity\TransferFromRailway;
use Module\Booking\Shared\Domain\Booking\Repository\Details\TransferFromRailwayRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Booking\Shared\Infrastructure\Models\Booking;
use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class TransferFromRailwayRepository extends AbstractDetailsRepository implements TransferFromRailwayRepositoryInterface
{
    public function find(BookingId $bookingId): ?TransferFromRailway
    {
        $booking = Booking::find($bookingId->value());
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }

        return $this->detailsFactory->buildByBooking($booking);
    }

    public function findOrFail(BookingId $bookingId): TransferFromRailway
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
        ?DateTimeInterface $arrivalDate,
        ?string $meetingTablet
    ): TransferFromRailway {
        $model = Transfer::create([
            'booking_id' => $bookingId->value(),
            'date_start' => $arrivalDate,
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

        return $this->detailsFactory->build(Transfer::find($model->id));
    }

    public function store(TransferFromRailway $details): bool
    {
        return (bool)Transfer::whereId($details->id()->value())->update([
            'date_start' => $details->arrivalDate(),
            'booking_transfer_details.data' => [
                'serviceInfo' => $this->serializeServiceInfo($details->serviceInfo()),
                'railwayStationId' => $details->railwayStationId()->value(),
                'trainNumber' => $details->trainNumber(),
                'meetingTablet' => $details->meetingTablet(),
                'carBids' => $details->carBids()->toData(),
            ]
        ]);
    }
}
