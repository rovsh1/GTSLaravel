<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Shared\Repository;

use Module\Booking\Domain\Shared\Entity\Request;
use Module\Booking\Domain\Shared\ValueObject\BookingId;
use Module\Booking\Domain\Shared\ValueObject\RequestId;
use Module\Booking\Domain\Shared\ValueObject\RequestTypeEnum;
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
