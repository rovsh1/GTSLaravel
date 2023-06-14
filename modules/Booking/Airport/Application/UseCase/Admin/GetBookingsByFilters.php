<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Application\UseCase\Admin;

use Module\Booking\Common\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Booking\Common\Infrastructure\Models\Booking;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetBookingsByFilters implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository
    ) {}

    public function execute(array $filters = []): mixed
    {
        return Booking::whereType(BookingTypeEnum::AIRPORT)->withAirportDetails()->get();
    }
}
