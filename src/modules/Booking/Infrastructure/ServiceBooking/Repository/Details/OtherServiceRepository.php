<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\ServiceBooking\Repository\Details;

use Module\Booking\Domain\Booking\Entity\OtherService;
use Module\Booking\Domain\Booking\Repository\Details\OtherServiceRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\ServiceInfo;
use Module\Booking\Infrastructure\ServiceBooking\Models\Booking;
use Module\Booking\Infrastructure\ServiceBooking\Models\Details\Other;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class OtherServiceRepository extends AbstractDetailsRepository implements OtherServiceRepositoryInterface
{
    public function find(BookingId $bookingId): ?OtherService
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
        ?string $description,
    ): OtherService {
        $model = Other::create([
            'booking_id' => $bookingId->value(),
            'service_id' => $serviceInfo->id(),
            'data' => [
                'serviceInfo' => $this->serializeServiceInfo($serviceInfo),
                'description' => $description,
            ]
        ]);

        return $this->detailsFactory->build(Other::find($model->id));
    }

    public function store(OtherService $details): bool
    {
        return (bool)Other::whereId($details->id()->value())->update([
            'data' => [
                'serviceInfo' => $this->serializeServiceInfo($details->serviceInfo()),
                'description' => $details->description(),
            ]
        ]);
    }
}
