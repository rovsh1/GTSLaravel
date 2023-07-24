<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Repository;

use Module\Booking\Common\Domain\Entity\Invoice;

interface InvoiceRepositoryInterface
{
    public function create(int $bookingId): Invoice;

    /**
     * @param int $bookingId
     * @return Invoice[]
     */
    public function findByBookingId(int $bookingId): array;
}
