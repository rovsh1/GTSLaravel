<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Repository\Details;

use Module\Booking\Shared\Domain\Booking\Entity\OtherService;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;

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
