<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Domain\Repository;

use Pkg\Booking\Requesting\Domain\Entity\BookingRequest;
use Pkg\Booking\Requesting\Domain\ValueObject\RequestId;
use Sdk\Booking\Enum\RequestTypeEnum;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Shared\ValueObject\File;

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
