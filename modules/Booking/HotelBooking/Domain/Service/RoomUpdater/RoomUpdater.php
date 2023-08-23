<?php

namespace Module\Booking\HotelBooking\Domain\Service\RoomUpdater;

use Module\Booking\HotelBooking\Domain\Adapter\HotelRoomAdapterInterface;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\Entity\RoomBooking;
use Module\Booking\HotelBooking\Domain\Event\RoomAdded;
use Module\Booking\HotelBooking\Domain\Event\RoomDeleted;
use Module\Booking\HotelBooking\Domain\Event\RoomEdited;
use Module\Booking\HotelBooking\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Repository\RoomBookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\QuotaManager;
use Module\Booking\HotelBooking\Domain\Service\RoomUpdater\Validator\ClientResidencyValidator;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingId;
use Module\Shared\Domain\Service\SafeExecutorInterface;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\ModuleInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class RoomUpdater
{
    /** @var DomainEventInterface[] $events */
    private array $events = [];

    public function __construct(
        private readonly ModuleInterface $module,
        private readonly RoomBookingRepositoryInterface $roomBookingRepository,
        private readonly HotelRoomAdapterInterface $hotelRoomAdapter,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly SafeExecutorInterface $executor,
        private readonly QuotaManager $quotaManager
    ) {}

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

    public function delete(Booking $booking, RoomBookingId $roomBookingId): void
    {
        $this->doAction(function () use ($booking, $roomBookingId) {
            $this->roomBookingRepository->delete($roomBookingId->value());
            $this->processQuota($booking);
            $this->events[] = new RoomDeleted($booking, $roomBookingId);
        });
    }

    private function doAdd(UpdateDataHelper $dataHelper): void
    {
        $this->makePipeline()->send($dataHelper);
        $roomBooking = $this->roomBookingRepository->create(
            $dataHelper->booking->id(),
            $dataHelper->status,
            $dataHelper->roomInfo,
            $dataHelper->guests,
            $dataHelper->details,
            $dataHelper->price
        );
        $this->processQuota($dataHelper->booking);
        $this->events[] = new RoomAdded($dataHelper->booking, $roomBooking);
    }

    private function doUpdate(RoomBooking $currentRoomBooking, UpdateDataHelper $dataHelper): void
    {
        $this->makePipeline()->send($dataHelper);
        $this->roomBookingRepository->store($currentRoomBooking);
        $this->events[] = new RoomEdited($dataHelper->booking, $currentRoomBooking);
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
        $this->events[] = new RoomDeleted($dataHelper->booking, $currentRoomBooking->id());
    }

    private function processQuota(Booking $booking): void
    {
        $booking = $this->bookingRepository->find($booking->id()->value());
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }
        $this->quotaManager->process($booking);
    }

    private function makePipeline(): ValidatorPipeline
    {
        return (new ValidatorPipeline($this->module))
            ->through(ClientResidencyValidator::class);
    }

    private function doAction(\Closure $action): void
    {
        $this->executor->execute($action);
        $this->eventDispatcher->dispatch(...$this->events);
    }
}