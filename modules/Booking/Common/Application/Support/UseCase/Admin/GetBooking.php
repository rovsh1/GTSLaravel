<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Support\UseCase\Admin;

use Module\Booking\Common\Application\Dto\BookingDto;
use Module\Booking\Common\Domain\Repository\BookingRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class GetBooking implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository
    ) {}

    public function execute(int $id): ?BookingDto
    {
        $booking = $this->repository->find($id);
        if ($booking === null) {
            throw new EntityNotFoundException("Booking not found [{$id}]");
        }
        return BookingDto::fromDomain($booking);
    }
}
