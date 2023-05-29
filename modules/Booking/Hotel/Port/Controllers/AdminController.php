<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Port\Controllers;

use Carbon\CarbonPeriod;
use Custom\Framework\Contracts\Bus\CommandBusInterface;
use Custom\Framework\Contracts\Bus\QueryBusInterface;
use Custom\Framework\Foundation\Exception\EntityNotFoundException;
use Custom\Framework\PortGateway\Request;
use Illuminate\Validation\Rules\Enum;
use Module\Booking\Hotel\Application\Command\Admin\CreateBooking;
use Module\Booking\Hotel\Application\Dto\BookingDto;
use Module\Booking\Hotel\Application\Dto\DetailsDto;
use Module\Booking\Hotel\Domain\Adapter\HotelRoomAdapterInterface;
use Module\Booking\Hotel\Domain\Entity\Details\Room;
use Module\Booking\Hotel\Domain\Exception\TooManyRoomGuests;
use Module\Booking\Hotel\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Hotel\Domain\Repository\DetailsRepositoryInterface;
use Module\Booking\Hotel\Domain\ValueObject\Details\Condition;
use Module\Booking\Hotel\Domain\ValueObject\Details\Guest;
use Module\Booking\Hotel\Domain\ValueObject\Details\GuestCollection;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomStatusEnum;
use Module\Booking\Hotel\Infrastructure\Models\Booking;
use Module\Shared\Application\Exception\BaseApplicationException;
use Module\Shared\Domain\Exception\DomainEntityExceptionInterface;
use Module\Shared\Domain\ValueObject\GenderEnum;
use Module\Shared\Domain\ValueObject\Percent;
use Module\Shared\Domain\ValueObject\TimePeriod;

class AdminController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly CommandBusInterface $commandBus,
        private readonly BookingRepositoryInterface $repository,
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly HotelRoomAdapterInterface $hotelRoomAdapter
    ) {}

    public function getBookings(Request $request): mixed
    {
        $request->validate([
            //filters
        ]);

        return Booking::query()->get();
    }

    public function getBooking(Request $request): mixed
    {
        $request->validate([
            'id' => ['required', 'int'],
        ]);

        $booking = $this->repository->find($request->id);
        if ($booking === null) {
            throw new EntityNotFoundException("Booking not found [{$request->id}]");
        }
        return BookingDto::fromDomain($booking);
    }

    public function getBookingDetails(Request $request): mixed
    {
        $request->validate([
            'id' => ['required', 'int'],
        ]);

        $details = $this->detailsRepository->find($request->id);
        if ($details === null) {
            throw new EntityNotFoundException("Details not found [{$request->id}]");
        }
        return DetailsDto::fromDomain($details);
    }

    public function createBooking(Request $request): int
    {
        $request->validate([
            'cityId' => ['required', 'int'],
            'clientId' => ['required', 'int'],
            'hotelId' => ['required', 'int'],
            'creatorId' => ['required', 'int'],
            'orderId' => ['nullable', 'int'],
            'dateStart' => ['nullable', 'date'],
            'dateEnd' => ['nullable', 'date'],
            'note' => ['nullable', 'string'],
        ]);

        return $this->commandBus->execute(
            new CreateBooking(
                cityId: $request->cityId,
                clientId: $request->clientId,
                hotelId: $request->hotelId,
                period: new CarbonPeriod($request->dateStart, $request->dateEnd),
                creatorId: $request->creatorId,
                orderId: $request->orderId,
                note: $request->note,
            )
        );
    }

    public function addRoom(Request $request): void
    {
        $request->validate([
            'id' => ['required', 'int'],
            'roomId' => ['required', 'int'],
            'rateId' => ['required', 'int'],
            'status' => ['required', 'int'],
            'isResident' => ['required', 'bool'],
            'roomCount' => ['required', 'int'],
            'note' => ['nullable', 'string'],
            'discount' => ['nullable', 'int'],
            'earlyCheckIn' => ['nullable', 'array'],
            'lateCheckOut' => ['nullable', 'array'],
        ]);

        $details = $this->detailsRepository->find($request->id);
        $details->addRoom(
            new Room(
                id: $request->roomId,
                rateId: $request->rateId,
                status: RoomStatusEnum::from($request->status),
                guests: new GuestCollection(),
                isResident: $request->isResident,
                guestNote: $request->note,
                roomCount: $request->roomCount,
                earlyCheckIn: $request->earlyCheckIn !== null ? $this->buildMarkupCondition($request->earlyCheckIn) : null,
                lateCheckOut: $request->lateCheckOut !== null ? $this->buildMarkupCondition($request->lateCheckOut) : null,
                discount: new Percent($request->discount ?? 0),
            )
        );
        $this->detailsRepository->update($details);
    }

    public function updateRoom(Request $request): void
    {
        $request->validate([
            'id' => ['required', 'int'],
            'roomIndex' => ['required', 'int'],
            'roomId' => ['required', 'int'],
            'rateId' => ['required', 'int'],
            'status' => ['required', 'int'],
            'isResident' => ['required', 'bool'],
            'roomCount' => ['required', 'int'],
            'note' => ['nullable', 'string'],
            'discount' => ['nullable', 'int'],
            'earlyCheckIn' => ['nullable', 'array'],
            'lateCheckOut' => ['nullable', 'array'],
        ]);

        $details = $this->detailsRepository->find($request->id);
        $currentRoom = $details->rooms()->get($request->roomIndex);
        $guests = $currentRoom->guests();

        $hotelRoomSettings = $this->hotelRoomAdapter->findById($request->roomId);
        $expectedGuestCount = $guests->count();
        if ($expectedGuestCount > $hotelRoomSettings->guestsNumber) {
            $guests = $guests->slice(0, $hotelRoomSettings->guestsNumber);
        }
        $details->updateRoom(
            $request->roomIndex,
            new Room(
                id: $request->roomId,
                rateId: $request->rateId,
                status: RoomStatusEnum::from($request->status),
                guests: $guests,
                isResident: $request->isResident,
                guestNote: $request->note,
                roomCount: $request->roomCount,
                earlyCheckIn: $request->earlyCheckIn !== null ? $this->buildMarkupCondition($request->earlyCheckIn) : null,
                lateCheckOut: $request->lateCheckOut !== null ? $this->buildMarkupCondition($request->lateCheckOut) : null,
                discount: new Percent($request->discount ?? 0),
            )
        );
        $this->detailsRepository->update($details);
    }

    public function deleteRoom(Request $request): void
    {
        $request->validate([
            'id' => ['required', 'int'],
            'roomIndex' => ['required', 'int'],
        ]);
        $details = $this->detailsRepository->find($request->id);
        $details->deleteRoom($request->roomIndex);
        $this->detailsRepository->update($details);
    }

    public function addRoomGuest(Request $request): void
    {
        $request->validate([
            'id' => ['required', 'int'],
            'roomIndex' => ['required', 'int'],
            'fullName' => ['required', 'string'],
            'countryId' => ['nullable', 'int'],
            'gender' => ['nullable', new Enum(GenderEnum::class)],
        ]);

        try {
            $details = $this->detailsRepository->find($request->id);
            $room = $details->rooms()->get($request->roomIndex);
            $hotelRoomSettings = $this->hotelRoomAdapter->findById($room->id());
            $expectedGuestCount = $room->guests()->count() + 1;
            //@todo перенести валидацию в сервис
            if ($expectedGuestCount > $hotelRoomSettings->guestsNumber) {
                throw new TooManyRoomGuests(
                    "Room doesn't support {$expectedGuestCount} guests, max {$hotelRoomSettings->guestsNumber} available."
                );
            }

            $details->rooms()->get($request->roomIndex)->addGuest(
                new Guest(
                    $request->fullName,
                    $request->countryId,
                    GenderEnum::from($request->gender),
                )
            );
            $this->detailsRepository->update($details);
        } catch (DomainEntityExceptionInterface $e) {
            throw new BaseApplicationException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function updateRoomGuest(Request $request): void
    {
        $request->validate([
            'id' => ['required', 'int'],
            'roomIndex' => ['required', 'int'],
            'guestIndex' => ['required', 'int'],
            'fullName' => ['required', 'string'],
            'countryId' => ['nullable', 'int'],
            'gender' => ['nullable', new Enum(GenderEnum::class)],
        ]);

        $details = $this->detailsRepository->find($request->id);
        $room = $details->rooms()->get($request->roomIndex);
        $newGuest = new Guest($request->fullName, $request->countryId, GenderEnum::from($request->gender));
        $room->updateGuest($request->guestIndex, $newGuest);
        $this->detailsRepository->update($details);
    }

    private function buildMarkupCondition(array $data): Condition
    {
        return new Condition(
            new TimePeriod($data['from'], $data['to']),
            new Percent($data['percent'])
        );
    }
}
