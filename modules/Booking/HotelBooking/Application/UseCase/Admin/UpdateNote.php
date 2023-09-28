<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\UseCase\Admin;

use Module\Booking\Common\Domain\Service\BookingUpdater;
use Module\Booking\HotelBooking\Domain\Repository\BookingRepositoryInterface;
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