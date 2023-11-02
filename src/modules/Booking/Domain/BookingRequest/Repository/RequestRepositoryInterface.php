<?php

declare(strict_types=1);

namespace Module\Booking\Domain\BookingRequest\Repository;

use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\BookingRequest\BookingRequest;
use Module\Booking\Domain\BookingRequest\ValueObject\RequestId;
use Module\Booking\Domain\BookingRequest\ValueObject\RequestTypeEnum;
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
