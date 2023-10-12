<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\ServiceBooking\Repository\Details;

use DateTimeInterface;
use Module\Booking\Domain\Booking\Entity\TransferFromAirport;
use Module\Booking\Domain\Booking\Repository\Details\TransferFromAirportRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Domain\Booking\ValueObject\ServiceInfo;
use Module\Booking\Infrastructure\ServiceBooking\Models\Booking;
use Module\Booking\Infrastructure\ServiceBooking\Models\Details\Transfer;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class TransferFromAirportRepository extends AbstractDetailsRepository implements TransferFromAirportRepositoryInterface
{
    public function find(BookingId $bookingId): ?TransferFromAirport
    {
        $booking = Booking::find($bookingId->value());
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }

        return $this->detailsFactory->buildByBooking($booking);
    }

    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        int $airportId,
        CarBidCollection $carBids,
        ?string $flightNumber,
        ?DateTimeInterface $arrivalDate,
        ?string $meetingTablet
    ): TransferFromAirport {
        $model = Transfer::create([
            'booking_id' => $bookingId->value(),
            'date_start' => $arrivalDate,
            'service_id' => $serviceInfo->id(),
            'data' => [
                'serviceInfo' => $this->serializeServiceInfo($serviceInfo),
                'airportId' => $airportId,
                'flightNumber' => $flightNumber,
                'meetingTablet' => $meetingTablet,
                'carBids' => $carBids->toData(),
            ]
        ]);

        return $this->detailsFactory->build(Transfer::find($model->id));
    }

    public function store(TransferFromAirport $details): bool
    {
        return (bool)Transfer::whereId($details->id()->value())->update([
            'data' => [
                'serviceInfo' => $this->serializeServiceInfo($details->serviceInfo()),
                'airportId' => $details->airportId(),
                'flightNumber' => $details->flightNumber(),
                'meetingTablet' => $details->meetingTablet(),
                'carBids' => $details->carBids()->toData(),
            ]
        ]);
    }
}
