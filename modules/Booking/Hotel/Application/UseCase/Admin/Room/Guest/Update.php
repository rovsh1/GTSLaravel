<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin\Room\Guest;

use Module\Booking\Hotel\Application\Request\Guest\UpdateRoomGuestDto;
use Module\Booking\Hotel\Domain\Repository\DetailsRepositoryInterface;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking\Guest;
use Module\Shared\Domain\ValueObject\GenderEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Update implements UseCaseInterface
{
    public function __construct(
        private readonly DetailsRepositoryInterface $detailsRepository
    ) {}

    public function execute(UpdateRoomGuestDto $request): void
    {
        $details = $this->detailsRepository->find($request->bookingId);
        $room = $details->roomBookings()->get($request->roomIndex);
        $newGuest = new Guest(
            $request->fullName,
            $request->countryId,
            GenderEnum::from($request->gender),
            $request->isAdult
        );
        $room->updateGuest($request->guestIndex, $newGuest);
        $this->detailsRepository->update($details);
    }
}
