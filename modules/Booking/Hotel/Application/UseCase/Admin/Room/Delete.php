<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin\Room;

use Module\Booking\Common\Domain\Service\BookingUpdater;
use Module\Booking\Hotel\Infrastructure\Repository\BookingRepository;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Delete implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepository $repository,
        private readonly BookingUpdater $bookingUpdater,
    ) {}

    public function execute(int $bookingId, int $roomIndex): void
    {
        $details = $this->repository->find($bookingId);
        $details->deleteRoomBooking($roomIndex);
        $this->bookingUpdater->store($details);
    }
}
