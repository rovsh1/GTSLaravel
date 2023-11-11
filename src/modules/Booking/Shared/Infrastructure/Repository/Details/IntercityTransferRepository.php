<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Repository\Details;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Entity\IntercityTransfer;
use Module\Booking\Shared\Domain\Booking\Repository\Details\IntercityTransferRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Booking\Shared\Infrastructure\Models\Booking;
use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class IntercityTransferRepository extends AbstractDetailsRepository implements IntercityTransferRepositoryInterface
{
    public function find(BookingId $bookingId): ?IntercityTransfer
    {
        $booking = Booking::find($bookingId->value());
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }

        return $this->detailsFactory->buildByBooking($booking);
    }

    public function findOrFail(BookingId $bookingId): IntercityTransfer
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
        int $fromCityId,
        int $toCityId,
        CarBidCollection $carBids,
        bool $returnTripIncluded,
        ?DateTimeInterface $departureDate,
    ): IntercityTransfer {
        $model = Transfer::create([
            'booking_id' => $bookingId->value(),
            'date_start' => $departureDate,
            'service_id' => $serviceInfo->id(),
            'data' => [
                'serviceInfo' => $this->serializeServiceInfo($serviceInfo),
                'carBids' => $carBids->toData(),
                'fromCityId' => $fromCityId,
                'toCityId' => $toCityId,
                'returnTripIncluded' => $returnTripIncluded,
            ]
        ]);

        return $this->detailsFactory->build(Transfer::find($model->id));
    }

    public function store(IntercityTransfer $details): bool
    {
        return (bool)Transfer::whereId($details->id()->value())->update([
            'date_start' => $details->departureDate(),
            'booking_transfer_details.data' => [
                'serviceInfo' => $this->serializeServiceInfo($details->serviceInfo()),
                'fromCityId' => $details->fromCityId()->value(),
                'toCityId' => $details->toCityId()->value(),
                'returnTripIncluded' => $details->isReturnTripIncluded(),
                'carBids' => $details->carBids()->toData(),
            ]
        ]);
    }
}
