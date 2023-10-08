<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\UseCase;

use Module\Booking\Domain\HotelBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Shared\Service\BookingUpdater;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateNote implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly BookingUpdater $bookingUpdater,
    ) {}

    public function execute(int $id, ?string $note): void
    {
        $booking = $this->repository->find($id);
        $booking->setNote($note);
        $this->bookingUpdater->store($booking);
    }
}