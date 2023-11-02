<?php

declare(strict_types=1);

namespace Module\Booking\Application\UseCase\Admin;

use Module\Booking\Application\Dto\ServiceBooking\BookingDto;
use Module\Booking\Application\Factory\BookingDtoFactory;
use Module\Booking\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class GetBooking implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly BookingDtoFactory $factory,
    ) {}

    public function execute(int $id): ?BookingDto
    {
        $booking = $this->repository->find(new BookingId($id));
        if ($booking === null) {
            throw new EntityNotFoundException("Booking not found [$id]");
        }

        return $this->factory->createFromEntity($booking);
    }
}
