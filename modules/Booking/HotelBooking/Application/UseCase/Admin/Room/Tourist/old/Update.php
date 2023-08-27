<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\UseCase\Admin\Room\Tourist;

use Module\Booking\HotelBooking\Application\Request\Guest\UpdateRoomGuestDto;
use Module\Booking\HotelBooking\Domain\Repository\RoomBookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\Guest;
use Module\Shared\Domain\ValueObject\GenderEnum;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Update implements UseCaseInterface
{
    public function __construct(
        private readonly RoomBookingRepositoryInterface $repository,
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    public function execute(UpdateRoomGuestDto $request): void
    {
        $roomBooking = $this->repository->find($request->roomBookingId);
        $newGuest = new Guest(
            $request->fullName,
            $request->countryId,
            GenderEnum::from($request->gender),
            $request->isAdult,
            $request->age
        );
        $roomBooking->updateGuest($request->guestIndex, $newGuest);
        $this->repository->store($roomBooking);
        $this->eventDispatcher->dispatch(...$roomBooking->pullEvents());
    }
}
