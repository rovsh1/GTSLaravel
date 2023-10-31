<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Order\Repository;

use Module\Booking\Domain\Order\Entity\Voucher;

interface VoucherRepositoryInterface
{
    public function create(int $bookingId): Voucher;

    /**
     * @param int $bookingId
     * @return Voucher[]
     */
    public function findByBookingId(int $bookingId): array;
}
