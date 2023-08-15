<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Entity;

use Carbon\CarbonImmutable;
use Module\Booking\Common\Domain\Entity\AbstractBooking;
use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Common\Domain\ValueObject\BookingPrice;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Booking\Common\Domain\ValueObject\OrderId;
use Module\Booking\HotelBooking\Domain\Event\BookingPeriodChanged;
use Module\Booking\HotelBooking\Domain\Event\RoomAdded;
use Module\Booking\HotelBooking\Domain\Event\RoomDeleted;
use Module\Booking\HotelBooking\Domain\Event\RoomEdited;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\AdditionalInfo;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\BookingPeriod;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\CancelConditions;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\HotelInfo;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBookingCollection;
use Module\Shared\Domain\ValueObject\Id;
use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;

final class Booking extends AbstractBooking
{
    public function __construct(
        BookingId $id,
        OrderId $orderId,
        BookingStatusEnum $status,
        CarbonImmutable $createdAt,
        Id $creatorId,
        BookingPrice $price,
        private ?string $note,
        private HotelInfo $hotelInfo,
        private BookingPeriod $period,
        private ?AdditionalInfo $additionalInfo,
        private RoomBookingCollection $roomBookings,
        private CancelConditions $cancelConditions,
        private QuotaProcessingMethodEnum $quotaProcessingMethod,
    ) {
        parent::__construct($id, $orderId, $status, $createdAt, $creatorId, $price);
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

    public function type(): BookingTypeEnum
    {
        return BookingTypeEnum::HOTEL;
    }

    public function quotaProcessingMethod(): QuotaProcessingMethodEnum
    {
        return $this->quotaProcessingMethod;
    }

    public function setQuotaProcessingMethod(QuotaProcessingMethodEnum $quotaProcessingMethod): void
    {
        $this->quotaProcessingMethod = $quotaProcessingMethod;
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

    public function updateRoomBooking(int $id, RoomBooking $booking): void
    {
        $roomIndex = $this->roomBookings->search(
            fn(RoomBooking $roomBooking) => $roomBooking->id()->value() === $id
        );
        $this->roomBookings->offsetSet($roomIndex, $booking);

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

    public function deleteRoomBooking(int $id): void
    {
        $roomIndex = $this->roomBookings->search(
            fn(RoomBooking $roomBooking) => $roomBooking->id()->value() === $id
        );
        $this->roomBookings->offsetUnset($roomIndex);
        $this->pushEvent(
            new RoomDeleted(
                $this,
                $this->hotelInfo()->id(),
                $roomIndex,
                '',//@todo надо ли?
            )
        );
    }

    public function cancelConditions(): CancelConditions
    {
        return $this->cancelConditions;
    }

    public function note(): ?string
    {
        return $this->note;
    }

    public function setNote(string|null $note): void
    {
        $this->note = $note;
    }

    public function setCancelConditions(CancelConditions $cancelConditions): void
    {
        $this->cancelConditions = $cancelConditions;
    }

    public function pullEvents(): array
    {
        $parentEvents = parent::pullEvents();
        $childrenEvents = [];
        foreach ($this->roomBookings as $roomBooking) {
            $childrenEvents[] = $roomBooking->pullEvents();
        }

        return array_merge($parentEvents, ...$childrenEvents);
    }
}
