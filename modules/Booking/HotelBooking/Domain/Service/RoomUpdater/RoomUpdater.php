<?php

namespace Module\Booking\HotelBooking\Domain\Service\RoomUpdater;

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

        $this->doAction(function () use ($currentRoomBooking, $dataHelper) {
            if ($currentRoomBooking->roomInfo()->id() === $dataHelper->hotelRoomId()) {
                $this->doUpdate($currentRoomBooking, $dataHelper);
            } else {
                $this->doReplace($currentRoomBooking, $dataHelper);
            }
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

    private function doUpdate($currentRoomBooking, UpdateDataHelper $dataHelper): void
    {
        $this->makePipeline()
//                ->withoutValidator(QuotaAvailabilityValidator::class)
            ->send($dataHelper);
        $roomBooking = $currentRoomBooking;
        $this->roomRepository->store($roomBooking);
        $this->events[] = new RoomEdited($roomBooking);
    }

    private function doReplace($currentRoomBooking, UpdateDataHelper $dataHelper): void
    {
        $this->doAdd($dataHelper);

        $this->roomRepository->remove($currentRoomBooking->id());
        $this->events[] = new RoomDeleted($currentRoomBooking);
    }

    private function makePipeline(): ValidatorPipeline
    {
        return (new ValidatorPipeline($this->module))
            ->through(ClientResidencyValidator::class)
            ->through(QuotaAvailabilityValidator::class);
    }

    private function doAction(\Closure $action): void
    {
        //start transaction
        $action();
        //commit
        $this->eventDispatcher->dispatch(...$this->events);
    }
}
