<?php

declare(strict_types=1);

namespace Module\Booking\Common\Infrastructure\Repository;

use Module\Booking\Common\Domain\Entity\Invoice as Entity;
use Module\Booking\Common\Domain\Factory\InvoiceFactory;
use Module\Booking\Common\Domain\Repository\InvoiceRepositoryInterface;
use Module\Booking\Common\Infrastructure\Models\Invoice as Model;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    public function __construct(
        private readonly InvoiceFactory $factory
    ) {}

    public function create(int $bookingId): Entity
    {
        $model = Model::create([
            'booking_id' => $bookingId,
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
