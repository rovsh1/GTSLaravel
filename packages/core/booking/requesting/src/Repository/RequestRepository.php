<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Repository;

use Pkg\Booking\Requesting\Domain\Entity\BookingRequest as Entity;
use Pkg\Booking\Requesting\Domain\Repository\RequestRepositoryInterface;
use Pkg\Booking\Requesting\Domain\ValueObject\RequestId;
use Pkg\Booking\Requesting\Models\BookingRequest as Model;
use Sdk\Booking\Enum\RequestTypeEnum;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Shared\ValueObject\File;

class RequestRepository implements RequestRepositoryInterface
{
    public function __construct(
        private readonly RequestFactory $factory
    ) {
    }

    public function find(RequestId $id): ?Entity
    {
        $model = Model::find($id->value());
        if ($model === null) {
            return null;
        }

        return $this->factory->createFrom($model);
    }

    public function create(BookingId $bookingId, RequestTypeEnum $type, File $file): Entity
    {
        $model = Model::create([
            'booking_id' => $bookingId->value(),
            'type' => $type,
            'file' => $file->guid()
        ]);

        return $this->factory->createFrom($model);
    }

    public function getLastChangeRequest(BookingId $bookingId): ?Entity
    {
        $model = Model::whereBookingId($bookingId->value())->whereType(RequestTypeEnum::CHANGE)->first();
        if ($model === null) {
            return null;
        }

        return $this->factory->createFrom($model);
    }

    /**
     * @param int $bookingId
     * @return Entity[]
     */
    public function findByBookingId(BookingId $bookingId): array
    {
        $models = Model::whereBookingId($bookingId->value())->get();

        return $this->factory->createCollectionFrom($models);
    }

    public function archiveByBooking(BookingId $bookingId): void
    {
        Model::whereBookingId($bookingId->value())->update(['is_archive' => true]);
    }
}
