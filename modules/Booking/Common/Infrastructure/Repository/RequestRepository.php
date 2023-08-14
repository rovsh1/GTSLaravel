<?php

declare(strict_types=1);

namespace Module\Booking\Common\Infrastructure\Repository;

use Module\Booking\Common\Domain\Entity\Request as Entity;
use Module\Booking\Common\Domain\Factory\RequestFactory;
use Module\Booking\Common\Domain\Repository\RequestRepositoryInterface;
use Module\Booking\Common\Domain\ValueObject\RequestTypeEnum;
use Module\Booking\Common\Infrastructure\Models\Request as Model;

class RequestRepository implements RequestRepositoryInterface
{
    public function __construct(
        private readonly RequestFactory $factory
    ) {}

    public function create(int $bookingId, RequestTypeEnum $type): Entity
    {
        $model = Model::create([
            'booking_id' => $bookingId,
            'type' => $type
        ]);

        return $this->factory->createFrom($model);
    }

    public function getLastChangeRequest(int $bookingId): ?Entity
    {
        $model = Model::whereBookingId($bookingId)->whereType(RequestTypeEnum::CHANGE)->first();
        if ($model === null) {
            return null;
        }

        return $this->factory->createFrom($model);
    }

    /**
     * @param int $bookingId
     * @return Entity[]
     */
    public function findByBookingId(int $bookingId): array
    {
        $models = Model::whereBookingId($bookingId)->get();

        return $this->factory->createCollectionFrom($models);
    }

    public function archiveByBooking(int $bookingId, RequestTypeEnum $type): void
    {
        Model::whereBookingId($bookingId)->whereType($type)->update(['is_archive' => true]);
    }
}
