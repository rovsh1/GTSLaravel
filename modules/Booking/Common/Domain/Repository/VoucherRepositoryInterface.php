<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Repository;

use Module\Booking\Common\Domain\Entity\Voucher;

interface VoucherRepositoryInterface
{
    public function create(int $bookingId): Voucher;

    /**
     * @param int $bookingId
     * @return Voucher[]
     */
    public function findByBookingId(int $bookingId): array;
}
