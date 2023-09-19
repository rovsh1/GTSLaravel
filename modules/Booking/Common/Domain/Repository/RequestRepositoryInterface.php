<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Repository;

use Module\Booking\Common\Domain\Entity\Request;
use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Common\Domain\ValueObject\RequestId;
use Module\Booking\Common\Domain\ValueObject\RequestTypeEnum;
use Module\Shared\ValueObject\File;

interface RequestRepositoryInterface
{
    public function find(RequestId $id): ?Request;

    public function create(BookingId $bookingId, RequestTypeEnum $type, File $file): Request;

    public function getLastChangeRequest(BookingId $bookingId): ?Request;

    /**
     * @param int $bookingId
     * @return Request[]
     */
    public function findByBookingId(BookingId $bookingId): array;

    public function archiveByBooking(BookingId $bookingId, RequestTypeEnum $type): void;
}
