<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\Shared\Support\UseCase;

use Module\Booking\Application\Admin\Shared\Factory\BookingDtoFactoryInterface;
use Module\Booking\Application\Admin\Shared\Response\BookingDto;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

abstract class AbstractGetBooking implements UseCaseInterface
{
    public function __construct(
        private $repository,
        private readonly BookingDtoFactoryInterface $factory,
    ) {
    }

    public function execute(int $id): ?BookingDto
    {
        /** @var BookingInterface $booking */
        $booking = $this->repository->find(new BookingId($id));
        if ($booking === null) {
            throw new EntityNotFoundException("Booking not found [$id]");
        }

        return $this->factory->createFromEntity($booking);
    }
}
