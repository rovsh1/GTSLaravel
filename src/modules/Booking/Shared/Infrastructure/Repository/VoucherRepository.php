<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Repository;

use Module\Booking\Shared\Domain\Voucher\Factory\VoucherFactory;
use Module\Booking\Shared\Domain\Voucher\Repository\VoucherRepositoryInterface;
use Module\Booking\Shared\Domain\Voucher\Voucher as Entity;
use Module\Booking\Shared\Infrastructure\Models\Voucher as Model;

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
