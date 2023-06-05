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

    /**
     * @param int $bookingId
     * @return Entity[]
     */
    public function findByBookingId(int $bookingId): array
    {
        $models = Model::whereBookingId($bookingId)->get();

        return $this->factory->createCollectionFrom($models);
    }
}
