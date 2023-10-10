<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\ServiceBooking\Repository\Details;

use DateTimeInterface;
use Module\Booking\Domain\ServiceBooking\Entity\TransferFromAirport;
use Module\Booking\Domain\ServiceBooking\Repository\Details\TransferFromAirportRepositoryInterface;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Booking\Domain\ServiceBooking\ValueObject\ServiceInfo;
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
        ?string $flightNumber,
        ?DateTimeInterface $arrivalDate,
        ?string $meetingTablet
    ): TransferFromAirport {
        $model = Transfer::create([
            'booking_id' => $bookingId->value(),
            'date_start' => $arrivalDate,
            'service_id' => $serviceInfo->id()->value(),
            'data' => [
                'serviceInfo' => $this->serializeServiceInfo($serviceInfo),
                'airportId' => $airportId,
                'flightNumber' => $flightNumber,
                'meetingTablet' => $meetingTablet,
            ]
        ]);

        return $this->detailsFactory->build(Transfer::find($model->id));
    }
}
