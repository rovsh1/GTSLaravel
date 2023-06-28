<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin\Room\Guest;

use Module\Booking\Common\Domain\Service\BookingUpdater;
use Module\Booking\Hotel\Application\Request\Guest\AddRoomGuestDto;
use Module\Booking\Hotel\Domain\Adapter\HotelRoomAdapterInterface;
use Module\Booking\Hotel\Domain\Exception\TooManyRoomGuests;
use Module\Booking\Hotel\Domain\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking\Guest;
use Module\Booking\Hotel\Infrastructure\Repository\BookingRepository;
use Module\Shared\Application\Exception\BaseApplicationException;
use Module\Shared\Domain\Exception\DomainEntityExceptionInterface;
use Module\Shared\Domain\ValueObject\GenderEnum;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Add implements UseCaseInterface
{
    public function __construct(
        private readonly RoomBookingRepositoryInterface $repository,
        private readonly HotelRoomAdapterInterface $hotelRoomAdapter,
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    public function execute(AddRoomGuestDto $request): void
    {
        try {
            $roomBooking = $this->repository->find($request->roomBookingId);
            $hotelRoomSettings = $this->hotelRoomAdapter->findById($roomBooking->roomInfo()->id());
            $expectedGuestCount = $roomBooking->guests()->count() + 1;
            //@todo перенести валидацию в сервис
            if ($expectedGuestCount > $hotelRoomSettings->guestsCount) {
                throw new TooManyRoomGuests(
                    "Room doesn't support {$expectedGuestCount} guests, max {$hotelRoomSettings->guestsCount} available."
                );
            }

            $roomBooking->addGuest(
                new Guest(
                    $request->fullName,
                    $request->countryId,
                    GenderEnum::from($request->gender),
                    $request->isAdult,
                    $request->age
                )
            );
            $this->repository->store($roomBooking);
            $this->eventDispatcher->dispatch(...$roomBooking->pullEvents());
        } catch (DomainEntityExceptionInterface $e) {
            throw new BaseApplicationException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
