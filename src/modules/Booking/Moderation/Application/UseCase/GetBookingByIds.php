<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase;

use Module\Booking\Moderation\Application\Dto\ServiceBooking\BookingDto;
use Module\Booking\Moderation\Application\Factory\BookingDtoFactory;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class GetBookingByIds implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly BookingDtoFactory $factory,
    ) {}

    public function execute(array $ids): array
    {
        return array_map(fn(int $id) => $this->getBookingDto(new BookingId($id)), $ids);
    }

    private function getBookingDto(BookingId $id): BookingDto
    {
        $booking = $this->repository->find($id);
        if ($booking === null) {
            throw new EntityNotFoundException("Booking not found [$id]");
        }

        return $this->factory->createFromEntity($booking);
    }
}
