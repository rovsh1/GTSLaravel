<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\Entity;

use Carbon\CarbonImmutable;
use Module\Booking\Common\Domain\Entity\AbstractBooking;
use Module\Booking\Common\Domain\ValueObject\BookingPrice;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Booking\Hotel\Domain\Event\BookingPeriodChanged;
use Module\Booking\Hotel\Domain\Event\GuestAdded;
use Module\Booking\Hotel\Domain\Event\GuestDeleted;
use Module\Booking\Hotel\Domain\Event\GuestEdited;
use Module\Booking\Hotel\Domain\Event\RoomAdded;
use Module\Booking\Hotel\Domain\Event\RoomDeleted;
use Module\Booking\Hotel\Domain\Event\RoomEdited;
use Module\Booking\Hotel\Domain\ValueObject\Details\AdditionalInfo;
use Module\Booking\Hotel\Domain\ValueObject\Details\BookingPeriod;
use Module\Booking\Hotel\Domain\ValueObject\Details\CancelConditions;
use Module\Booking\Hotel\Domain\ValueObject\Details\HotelInfo;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking\Guest;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBookingCollection;
use Module\Shared\Domain\ValueObject\Id;

final class Booking extends AbstractBooking
{
    public function __construct(
        Id $id,
        Id $orderId,
        BookingStatusEnum $status,
        BookingTypeEnum $type,
        CarbonImmutable $createdAt,
        Id $creatorId,
        private ?string $note = null,
        private HotelInfo $hotelInfo,
        private BookingPeriod $period,
        private ?AdditionalInfo $additionalInfo,
        private RoomBookingCollection $roomBookings,
        private CancelConditions $cancelConditions,
        private BookingPrice $price,
    ) {
        parent::__construct($id, $orderId, $status, $type, $createdAt, $creatorId);
    }

    public function hotelInfo(): HotelInfo
    {
        return $this->hotelInfo;
    }

    public function period(): BookingPeriod
    {
        return $this->period;
    }

    public function setPeriod(BookingPeriod $period): void
    {
        $this->period = $period;
        $this->pushEvent(new BookingPeriodChanged($this));
    }

    public function additionalInfo(): ?AdditionalInfo
    {
        return $this->additionalInfo;
    }

    public function setAdditionalInfo(?AdditionalInfo $additionalInfo): void
    {
        $this->additionalInfo = $additionalInfo;
    }

    public function roomBookings(): RoomBookingCollection
    {
        return $this->roomBookings;
    }

    public function addRoomBooking(RoomBooking $booking): void
    {
        $this->roomBookings->add($booking);
        $this->pushEvent(
            new RoomAdded(
                $this,
                $this->hotelInfo()->id(),
                $booking->roomInfo()->id(),
                $booking->roomInfo()->name()
            )
        );
    }

    public function updateRoomBooking(int $index, RoomBooking $booking): void
    {
        $this->roomBookings->offsetSet($index, $booking);
        $this->pushEvent(
            new RoomEdited(
                $this,
                $booking->roomInfo()->id(),
                $booking->roomInfo()->name(),
                '',
                '',
                '',
            )
        );
    }

    public function deleteRoomBooking(int $index): void
    {
        $this->roomBookings->offsetUnset($index);
        $this->pushEvent(
            new RoomDeleted(
                $this,
                $this->hotelInfo()->id(),
                $index,
                '',//@todo надо ли?
            )
        );
    }

    public function addRoomBookingGuest(int $roomIndex, Guest $guest): void
    {
        $this->roomBookings->get($roomIndex)->addGuest($guest);
        $this->pushEvent(
            new GuestAdded(
                $this,
                $roomIndex,
                $guest
            )
        );
    }

    public function updateRoomBookingGuest(int $roomIndex, int $guestIndex, Guest $guest): void
    {
        $this->roomBookings->get($roomIndex)->updateGuest($guestIndex, $guest);
        $this->pushEvent(
            new GuestEdited(
                $this,
                $roomIndex,
                $guest,
                '',
                '',
                ''
            )
        );
    }

    public function deleteRoomBookingGuest(int $roomIndex, int $guestIndex): void
    {
        $this->roomBookings->get($roomIndex)->deleteGuest($guestIndex);
        $this->pushEvent(
            new GuestDeleted(
                $this,
                $roomIndex,
                $guestIndex,
                ''//@todo надо ли?
            )
        );
    }

    public function cancelConditions(): CancelConditions
    {
        return $this->cancelConditions;
    }

    public function price(): BookingPrice
    {
        return $this->price;
    }

    public function note(): ?string
    {
        return $this->note;
    }

    public function setNote(string|null $note): void
    {
        $this->note = $note;
    }
}
