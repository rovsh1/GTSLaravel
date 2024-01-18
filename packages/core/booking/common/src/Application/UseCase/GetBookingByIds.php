<?php

declare(strict_types=1);

namespace Pkg\Booking\Common\Application\UseCase;

use Module\Booking\Moderation\Application\Factory\BookingDtoFactory;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\BookingIdCollection;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetBookingByIds implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly BookingDtoFactory $factory,
    ) {}

    public function execute(array $ids): array
    {
        $bookingIds = array_map(fn(int $id) => new BookingId($id), $ids);
        $bookings = $this->repository->getBookings(new BookingIdCollection($bookingIds));

        return $this->factory->collection($bookings);
    }
}
