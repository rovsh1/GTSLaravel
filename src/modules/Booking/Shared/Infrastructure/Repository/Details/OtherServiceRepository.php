<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Repository\Details;

use Module\Booking\Shared\Domain\Booking\Entity\OtherService;
use Module\Booking\Shared\Domain\Booking\Repository\Details\OtherServiceRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Booking\Shared\Infrastructure\Factory\Details\OtherServiceFactory;
use Module\Booking\Shared\Infrastructure\Models\Booking;
use Module\Booking\Shared\Infrastructure\Models\Details\Other;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class OtherServiceRepository extends AbstractServiceDetailsRepository implements OtherServiceRepositoryInterface
{
    public function find(BookingId $bookingId): ?OtherService
    {
        $booking = Booking::find($bookingId->value());
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }

        return $this->build($booking->otherDetails);
    }

    public function findOrFail(BookingId $bookingId): OtherService
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

        return $this->build(Other::find($model->id));
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

    private function build(Other $details): OtherService
    {
        return (new OtherServiceFactory())->build($details);
    }
}
