<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin\Room;

use Module\Booking\Hotel\Domain\Repository\DetailsRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Delete implements UseCaseInterface
{
    public function __construct(
        private readonly DetailsRepositoryInterface $detailsRepository
    ) {}

    public function execute(int $bookingId, int $roomIndex): void
    {
        $details = $this->detailsRepository->find($bookingId);
        $details->deleteRoomBooking($roomIndex);
        $this->detailsRepository->update($details);
    }
}
