<?php

namespace Module\Booking\Deprecated\HotelBooking\Service\DocumentGenerator;

use Module\Booking\Application\Admin\Shared\Service\StatusStorage;
use Module\Booking\Deprecated\HotelBooking\HotelBooking;
use Module\Booking\Domain\Booking\Adapter\HotelAdapterInterface;
use Module\Booking\Domain\Order\Repository\GuestRepositoryInterface;
use Module\Booking\Domain\Shared\Adapter\AdministratorAdapterInterface;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Domain\Shared\Entity\Voucher;
use Module\Booking\Domain\Shared\Service\DocumentGenerator\AbstractVoucherGenerator;
use Module\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Module\Shared\Enum\ContactTypeEnum;

class VoucherGenerator extends AbstractVoucherGenerator
{
    public function __construct(
        string $templatesPath,
        FileStorageAdapterInterface $fileStorageAdapter,
        private readonly HotelAdapterInterface $hotelAdapter,
        private readonly AdministratorAdapterInterface $administratorAdapter,
        private readonly StatusStorage $statusStorage,
        private readonly GuestRepositoryInterface $guestRepository,
    ) {
        parent::__construct($templatesPath, $fileStorageAdapter);
    }

    protected function getTemplateName(): string
    {
        return 'hotel.voucher';
    }

    protected function getBookingAttributes(BookingInterface $booking): array
    {
        $hotelDto = $this->hotelAdapter->findById($booking->hotelInfo()->id());
        $phones = collect($hotelDto->contacts)
            ->map(function (mixed $contactDto) {
                if ($contactDto->type === ContactTypeEnum::PHONE->value) {
                    return $contactDto->value;
                }

                return null;
            })
            ->filter()
            ->implode(', ');

        $administrator = $this->administratorAdapter->getManagerByBookingId($booking->id()->value());
        $guests = $this->getGuestsIndexedByRoomBooking($booking);

        return [
            'reservCreatedAt' => $booking->createdAt()->format('d.m.Y H:i:s'),
            'reservUpdatedAt' => now()->format('d.m.Y H:i:s'),//@todo правильно ли, что тут now()?
            'hotelName' => $booking->hotelInfo()->name(),
            'hotelPhone' => $phones,
            'cityName' => $hotelDto->cityName,
            'hotelAddress' => $hotelDto->address,
            'reservStartDate' => $booking->period()->dateFrom()->format('d.m.Y'),
            'reservEndDate' => $booking->period()->dateTo()->format('d.m.Y'),
            'reservNightCount' => $booking->period()->nightsCount(),
            'reservNumber' => $booking->id()->value(),
            'reservStatus' => $this->statusStorage->get($booking->status())->name,
            'rooms' => $booking->roomBookings(),
            'roomsGuests' => $guests,
            'hotelDefaultCheckInTime' => $booking->hotelInfo()->checkInTime()->value(),
            'hotelDefaultCheckOutTime' => $booking->hotelInfo()->checkOutTime()->value(),
            'managerName' => $administrator?->name ?? $administrator?->presentation,//@todo надо ли?
            'managerPhone' => $administrator?->phone,
            'managerEmail' => $administrator?->email,
        ];
    }

    protected function getVoucherAttributes(Voucher $voucher): array
    {
        return [
            'number' => $voucher->id()->value(),
            'createdAt' => $voucher->dateCreate()->format('d.m.Y H:i:s'),
        ];
    }

    public function getGuestsIndexedByRoomBooking(HotelBooking $booking): array
    {
        $guests = [];
        foreach ($booking->roomBookings() as $roomBooking) {
            $guests[$roomBooking->id()->value()] = $this->guestRepository->get($roomBooking->guestIds());
        }

        return $guests;
    }
}
