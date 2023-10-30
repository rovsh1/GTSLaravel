<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\Repository\Details;

use Module\Booking\Domain\Booking\Entity\OtherService;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\ServiceInfo;

interface OtherServiceRepositoryInterface
{
    public function find(BookingId $bookingId): ?OtherService;

    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        ?string $description,
    ): OtherService;

    public function store(OtherService $details): bool;
}
