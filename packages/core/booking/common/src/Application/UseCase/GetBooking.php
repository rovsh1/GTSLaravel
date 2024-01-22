<?php

declare(strict_types=1);

namespace Pkg\Booking\Common\Application\UseCase;

use Module\Booking\Moderation\Application\Dto\ServiceBooking\BookingDto;
use Module\Booking\Moderation\Application\Factory\BookingDtoFactory;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Sdk\Booking\ValueObject\BookingId;
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
