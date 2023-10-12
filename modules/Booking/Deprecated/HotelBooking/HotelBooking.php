<?php

declare(strict_types=1);

namespace Module\Booking\Deprecated\HotelBooking;

use Carbon\CarbonImmutable;
use Module\Booking\Deprecated\HotelBooking\Entity\RoomBooking;
use Module\Booking\Deprecated\HotelBooking\Event\BookingPeriodChanged;
use Module\Booking\Deprecated\HotelBooking\Event\RoomAdded;
use Module\Booking\Deprecated\HotelBooking\Event\RoomDeleted;
use Module\Booking\Deprecated\HotelBooking\Event\RoomEdited;
use Module\Booking\Deprecated\HotelBooking\ValueObject\Details\AdditionalInfo;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\BookingPeriod;
use Module\Booking\Domain\Booking\ValueObject\BookingPrices;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\HotelInfo;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingCollection;
use Module\Booking\Domain\Order\ValueObject\OrderId;
use Module\Booking\Domain\Shared\Entity\AbstractBooking;
use Module\Booking\Domain\Shared\ValueObject\BookingStatusEnum;
use Module\Booking\Domain\Shared\ValueObject\CancelConditions;
use Module\Booking\Domain\Shared\ValueObject\CreatorId;
use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;
use Module\Shared\Enum\ServiceTypeEnum;

final class HotelBooking extends AbstractBooking
{
    public function __construct(
        BookingId $id,
        OrderId $orderId,
        BookingStatusEnum $status,
        CarbonImmutable $createdAt,
        CreatorId $creatorId,
        BookingPrices $price,
        private ?string $note,
        private HotelInfo $hotelInfo,
        private BookingPeriod $period,
        private ?AdditionalInfo $additionalInfo,
        private RoomBookingCollection $roomBookings,
        private CancelConditions $cancelConditions,
        private readonly QuotaProcessingMethodEnum $quotaProcessingMethod,
    ) {
        parent::__construct($id, $orderId, $status, $createdAt, $creatorId, $price);
    }

    public function serviceType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::HOTEL_BOOKING;
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

    public function quotaProcessingMethod(): QuotaProcessingMethodEnum
    {
        return $this->quotaProcessingMethod;
    }

    public function addRoomBooking(RoomBooking $roomBooking): void
    {
        $this->roomBookings->add($roomBooking);
        $this->pushEvent(
            new RoomAdded(
                $this,
                $this->hotelInfo()->id(),
                $roomBooking->roomInfo()->id(),
                $roomBooking->roomInfo()->name()
            )
        );
    }

    public function updateRoomBooking(int $id, RoomBooking $roomBooking): void
    {
        $roomIndex = $this->roomBookings->search(
            fn(RoomBooking $roomBooking) => $roomBooking->id()->value() === $id
        );
        $this->roomBookings->offsetSet($roomIndex, $roomBooking);

        $this->pushEvent(
            new RoomEdited(
                $this,
                $roomBooking->roomInfo()->id(),
                $roomBooking->roomInfo()->name(),
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
