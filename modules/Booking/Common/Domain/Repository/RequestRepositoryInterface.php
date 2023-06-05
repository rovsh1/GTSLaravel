<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Repository;

use Module\Booking\Common\Domain\Entity\Request;
use Module\Booking\Common\Domain\ValueObject\RequestTypeEnum;

interface RequestRepositoryInterface
{
    public function create(int $bookingId, RequestTypeEnum $type): Request;

    /**
     * @param int $bookingId
     * @return Request[]
     */
    public function findByBookingId(int $bookingId): array;
}
