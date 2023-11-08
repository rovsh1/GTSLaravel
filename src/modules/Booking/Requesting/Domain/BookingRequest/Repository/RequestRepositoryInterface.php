<?php

declare(strict_types=1);

namespace Module\Booking\Requesting\Domain\BookingRequest\Repository;

use Module\Booking\Requesting\Domain\BookingRequest\BookingRequest;
use Module\Booking\Requesting\Domain\BookingRequest\ValueObject\RequestId;
use Module\Booking\Requesting\Domain\BookingRequest\ValueObject\RequestTypeEnum;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Shared\ValueObject\File;

interface RequestRepositoryInterface
{
    public function find(RequestId $id): ?BookingRequest;

    public function create(BookingId $bookingId, RequestTypeEnum $type, File $file): BookingRequest;

    public function getLastChangeRequest(BookingId $bookingId): ?BookingRequest;

    /**
     * @param BookingId $bookingId
     * @return BookingRequest[]
     */
    public function findByBookingId(BookingId $bookingId): array;

    public function archiveByBooking(BookingId $bookingId): void;
}
