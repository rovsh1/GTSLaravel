<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\Shared\Repository;

use Module\Booking\Domain\Shared\Entity\Voucher as Entity;
use Module\Booking\Domain\Shared\Factory\VoucherFactory;
use Module\Booking\Domain\Shared\Repository\VoucherRepositoryInterface;
use Module\Booking\Infrastructure\Shared\Models\Voucher as Model;

class VoucherRepository implements VoucherRepositoryInterface
{
    public function __construct(
        private readonly VoucherFactory $factory
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
