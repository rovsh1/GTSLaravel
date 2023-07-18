<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Support\UseCase\Admin;

use Module\Booking\Common\Application\Factory\BookingDtoFactoryInterface;
use Module\Booking\Common\Application\Response\BookingDto;
use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\Repository\BookingRepositoryInterface;
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
