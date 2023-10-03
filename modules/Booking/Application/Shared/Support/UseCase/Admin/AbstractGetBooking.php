<?php

declare(strict_types=1);

namespace Module\Booking\Application\Shared\Support\UseCase\Admin;

use Module\Booking\Application\Shared\Factory\BookingDtoFactoryInterface;
use Module\Booking\Application\Shared\Response\BookingDto;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Domain\Shared\Repository\BookingRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

abstract class AbstractGetBooking implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly BookingDtoFactoryInterface $factory,
    ) {
    }

    public function execute(int $id): ?BookingDto
    {
        /** @var BookingInterface $booking */
        $booking = $this->repository->find($id);
        if ($booking === null) {
            throw new EntityNotFoundException("Booking not found [{$id}]");
        }

        return $this->factory->createFromEntity($booking);
    }
}
