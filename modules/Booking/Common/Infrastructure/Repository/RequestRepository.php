<?php

declare(strict_types=1);

namespace Module\Booking\Common\Infrastructure\Repository;

use Module\Booking\Common\Domain\Entity\Request as Entity;
use Module\Booking\Common\Domain\Factory\RequestFactory;
use Module\Booking\Common\Domain\Repository\RequestRepositoryInterface;
use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Common\Domain\ValueObject\RequestId;
use Module\Booking\Common\Domain\ValueObject\RequestTypeEnum;
use Module\Booking\Common\Infrastructure\Models\Request as Model;
use Module\Shared\ValueObject\File;

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
            $file->guid()
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

    public function archiveByBooking(BookingId $bookingId, RequestTypeEnum $type): void
    {
        Model::whereBookingId($bookingId->value())->whereType($type)->update(['is_archive' => true]);
    }
}
