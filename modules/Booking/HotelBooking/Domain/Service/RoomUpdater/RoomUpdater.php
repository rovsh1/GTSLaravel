<?php

namespace Module\Booking\HotelBooking\Domain\Service\RoomUpdater;

use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\HotelBooking\Domain\Adapter\HotelRoomAdapterInterface;
use Module\Booking\HotelBooking\Domain\Entity\RoomBooking;
use Module\Booking\HotelBooking\Domain\Event\RoomAdded;
use Module\Booking\HotelBooking\Domain\Event\RoomDeleted;
use Module\Booking\HotelBooking\Domain\Event\RoomEdited;
use Module\Booking\HotelBooking\Domain\Repository\RoomBookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Service\RoomUpdater\Validator\ClientResidencyValidator;
use Module\Booking\HotelBooking\Domain\Service\RoomUpdater\Validator\QuotaAvailabilityValidator;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\ModuleInterface;

class RoomUpdater
{
    /** @var DomainEventInterface[] $events */
    private array $events = [];

    public function __construct(
        private readonly ModuleInterface $module,
        private readonly RoomBookingRepositoryInterface $roomBookingRepository,
        private readonly HotelRoomAdapterInterface $hotelRoomAdapter,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function add(UpdateDataHelper $dataHelper): void
    {
        $this->doAction(fn() => $this->doAdd($dataHelper));
    }

    public function update(RoomBookingId $roomBookingId, UpdateDataHelper $dataHelper): void
    {
        $currentRoomBooking = $this->roomBookingRepository->find($roomBookingId->value());
        $action = function () use ($currentRoomBooking, $dataHelper): void {
            if ($currentRoomBooking->roomInfo()->id() !== $dataHelper->roomInfo->id()) {
                $this->doReplace($currentRoomBooking, $dataHelper);
                return;
            }

            $this->doUpdate($currentRoomBooking, $dataHelper);
        };
        $this->doAction($action);
    }

    public function delete(BookingId $bookingId, RoomBookingId $roomBookingId): void
    {
        $this->doAction(function () use ($bookingId, $roomBookingId) {
            $this->roomBookingRepository->delete($roomBookingId->value());
            //@todo возврат квот
            $this->events[] = new RoomDeleted($bookingId);
        });
    }

    private function doAdd(UpdateDataHelper $dataHelper): void
    {
        $this->makePipeline()->send($dataHelper);
        $roomBooking = $this->roomBookingRepository->create(
            $dataHelper->bookingId->value(),
            $dataHelper->status,
            $dataHelper->roomInfo,
            $dataHelper->guests,
            $dataHelper->details,
            $dataHelper->price
        );
        $this->processQuota();
        $this->events[] = new RoomAdded($roomBooking);
    }

    private function doUpdate(RoomBooking $currentRoomBooking, UpdateDataHelper $dataHelper): void
    {
        $this->makePipeline(false)->send($dataHelper);
        $roomBooking = $currentRoomBooking;
        $this->roomBookingRepository->store($roomBooking);
        $this->events[] = new RoomEdited($roomBooking);
    }

    private function doReplace(RoomBooking $currentRoomBooking, UpdateDataHelper $dataHelper): void
    {
        $hotelRoomDto = $this->hotelRoomAdapter->findById($dataHelper->roomInfo->id());
        $expectedGuestCount = $dataHelper->guests->count();
        if ($expectedGuestCount > $hotelRoomDto->guestsCount) {
            $dataHelper->guests->slice(0, $hotelRoomDto->guestsCount);
        }
        $this->doAdd($dataHelper);
        $this->roomBookingRepository->delete($currentRoomBooking->id()->value());
        $this->events[] = new RoomDeleted($currentRoomBooking);
    }

    private function processQuota(): void
    {
    }

    private function makePipeline(bool $needValidateQuota = true): ValidatorPipeline
    {
        return (new ValidatorPipeline($this->module))
            ->through(ClientResidencyValidator::class)
            ->throughWhen($needValidateQuota, QuotaAvailabilityValidator::class);
    }

    private function doAction(\Closure $action): void
    {
        //start transaction
        $action();
        //commit
        $this->eventDispatcher->dispatch(...$this->events);
    }
}
