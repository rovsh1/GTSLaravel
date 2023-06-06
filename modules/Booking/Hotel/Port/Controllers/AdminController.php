<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Port\Controllers;

use Illuminate\Validation\Rules\Enum;
use Module\Booking\Hotel\Application\Dto\DetailsDto;
use Module\Booking\Hotel\Domain\Adapter\HotelRoomAdapterInterface;
use Module\Booking\Hotel\Domain\Exception\TooManyRoomGuests;
use Module\Booking\Hotel\Domain\Repository\DetailsRepositoryInterface;
use Module\Booking\Hotel\Domain\ValueObject\Details\Condition;
use Module\Booking\Hotel\Domain\ValueObject\Details\GuestCollection;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking\Guest;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking\RoomBookingDetails;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking\RoomBookingStatusEnum;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking\RoomInfo;
use Module\Shared\Application\Exception\BaseApplicationException;
use Module\Shared\Domain\Exception\DomainEntityExceptionInterface;
use Module\Shared\Domain\ValueObject\GenderEnum;
use Module\Shared\Domain\ValueObject\Id;
use Module\Shared\Domain\ValueObject\Percent;
use Module\Shared\Domain\ValueObject\TimePeriod;
use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;
use Sdk\Module\PortGateway\Request;

class AdminController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly HotelRoomAdapterInterface $hotelRoomAdapter
    ) {}

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
        $hotelRoomDto = $this->hotelRoomAdapter->findById($request->roomId);
        $details->addRoomBooking(
            new RoomBooking(
                status: RoomBookingStatusEnum::from($request->status),
                roomInfo: new RoomInfo(
                    $hotelRoomDto->id,
                    $hotelRoomDto->name,
                ),
                guests: new GuestCollection(),
                details: new RoomBookingDetails(
                    rateId: $request->rateId,
                    isResident: $request->isResident,
                    guestNote: $request->note,
                    roomCount: $request->roomCount,
                    earlyCheckIn: $request->earlyCheckIn !== null ? $this->buildMarkupCondition($request->earlyCheckIn) : null,
                    lateCheckOut: $request->lateCheckOut !== null ? $this->buildMarkupCondition($request->lateCheckOut) : null,
                    discount: new Percent($request->discount ?? 0),
                ),
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
        $currentRoom = $details->roomBookings()->get($request->roomIndex);
        $guests = $currentRoom->guests();

        $hotelRoomDto = $this->hotelRoomAdapter->findById($request->roomId);
        $expectedGuestCount = $guests->count();
        if ($expectedGuestCount > $hotelRoomDto->guestsNumber) {
            $guests = $guests->slice(0, $hotelRoomDto->guestsNumber);
        }
        $details->updateRoomBooking(
            $request->roomIndex,
            new RoomBooking(
                status: RoomBookingStatusEnum::from($request->status),
                roomInfo: new RoomInfo(
                    $hotelRoomDto->id,
                    $hotelRoomDto->name,
                ),
                guests: $guests,
                details: new RoomBookingDetails(
                    rateId: $request->rateId,
                    isResident: $request->isResident,
                    guestNote: $request->note,
                    roomCount: $request->roomCount,
                    earlyCheckIn: $request->earlyCheckIn !== null ? $this->buildMarkupCondition($request->earlyCheckIn) : null,
                    lateCheckOut: $request->lateCheckOut !== null ? $this->buildMarkupCondition($request->lateCheckOut) : null,
                    discount: new Percent($request->discount ?? 0),
                ),
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
        $details->deleteRoomBooking($request->roomIndex);
        $this->detailsRepository->update($details);
    }

    public function addRoomGuest(Request $request): void
    {
        $request->validate([
            'id' => ['required', 'int'],
            'roomIndex' => ['required', 'int'],
            'fullName' => ['required', 'string'],
            'isAdult' => ['required', 'boolean'],
            'countryId' => ['nullable', 'int'],
            'gender' => ['nullable', new Enum(GenderEnum::class)],
        ]);

        try {
            $details = $this->detailsRepository->find($request->id);
            $room = $details->roomBookings()->get($request->roomIndex);
            $hotelRoomSettings = $this->hotelRoomAdapter->findById($room->roomInfo()->id());
            $expectedGuestCount = $room->guests()->count() + 1;
            //@todo перенести валидацию в сервис
            if ($expectedGuestCount > $hotelRoomSettings->guestsNumber) {
                throw new TooManyRoomGuests(
                    "Room doesn't support {$expectedGuestCount} guests, max {$hotelRoomSettings->guestsNumber} available."
                );
            }

            $details->roomBookings()->get($request->roomIndex)->addGuest(
                new Guest(
                    $request->fullName,
                    $request->countryId,
                    GenderEnum::from($request->gender),
                    $request->isAdult,
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
            'isAdult' => ['required', 'boolean'],
            'countryId' => ['nullable', 'int'],
            'gender' => ['nullable', new Enum(GenderEnum::class)],
        ]);

        $details = $this->detailsRepository->find($request->id);
        $room = $details->roomBookings()->get($request->roomIndex);
        $newGuest = new Guest($request->fullName, $request->countryId, GenderEnum::from($request->gender), $request->isAdult);
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
