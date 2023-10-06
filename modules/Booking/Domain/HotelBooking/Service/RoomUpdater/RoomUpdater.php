<?php

namespace Module\Booking\Domain\HotelBooking\Service\RoomUpdater;

use Module\Booking\Domain\HotelBooking\Adapter\HotelRoomAdapterInterface;
use Module\Booking\Domain\HotelBooking\Entity\RoomBooking;
use Module\Booking\Domain\HotelBooking\Event\RoomAdded;
use Module\Booking\Domain\HotelBooking\Event\RoomDeleted;
use Module\Booking\Domain\HotelBooking\Event\RoomEdited;
use Module\Booking\Domain\HotelBooking\Exception\InvalidRoomResidency;
use Module\Booking\Domain\HotelBooking\HotelBooking;
use Module\Booking\Domain\HotelBooking\Repository\BookingGuestRepositoryInterface;
use Module\Booking\Domain\HotelBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\HotelBooking\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Domain\HotelBooking\Service\QuotaManager\Exception\ClosedRoomDateQuota;
use Module\Booking\Domain\HotelBooking\Service\QuotaManager\Exception\NotEnoughRoomDateQuota;
use Module\Booking\Domain\HotelBooking\Service\QuotaManager\Exception\NotFoundRoomDateQuota;
use Module\Booking\Domain\HotelBooking\Service\QuotaManager\QuotaManager;
use Module\Booking\Domain\HotelBooking\Service\RoomUpdater\Validator\ClientResidencyValidator;
use Module\Booking\Domain\HotelBooking\Service\RoomUpdater\Validator\ExistRoomPriceValidator;
use Module\Booking\Domain\HotelBooking\ValueObject\Details\RoomBooking\RoomBookingId;
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
        private readonly BookingGuestRepositoryInterface $bookingGuestRepository,
        private readonly SafeExecutorInterface $executor,
        private readonly QuotaManager $quotaManager
    ) {}

    /**
     * @param UpdateDataHelper $dataHelper
     * @return void
     * @throws EntityNotFoundException
     * @throws InvalidRoomResidency
     * @throws NotFoundRoomDateQuota
     * @throws ClosedRoomDateQuota
     * @throws NotEnoughRoomDateQuota
     */
    public function add(UpdateDataHelper $dataHelper): void
    {
        $this->doAction(fn() => $this->doAdd($dataHelper));
    }

    /**
     * @param RoomBookingId $roomBookingId
     * @param UpdateDataHelper $dataHelper
     * @return void
     * @throws EntityNotFoundException
     * @throws InvalidRoomResidency
     * @throws NotFoundRoomDateQuota
     * @throws ClosedRoomDateQuota
     * @throws NotEnoughRoomDateQuota
     */
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

    public function delete(HotelBooking $booking, RoomBookingId $roomBookingId): void
    {
        $this->doAction(function () use ($booking, $roomBookingId) {
            $this->roomBookingRepository->delete($roomBookingId->value());
            $this->processQuota($booking);
            $this->events[] = new RoomDeleted($booking, $roomBookingId);
        });
    }

    private function doAdd(UpdateDataHelper $dataHelper): RoomBooking
    {
        $this->makePipeline()->send($dataHelper);
        $roomBooking = $this->roomBookingRepository->create(
            $dataHelper->booking->id(),
            $dataHelper->roomInfo,
            $dataHelper->details,
            $dataHelper->price
        );
        $this->processQuota($dataHelper->booking);
        //@todo заменить processQuota на ивент RoomAdding (который процессит по листенеру)
        $this->events[] = new RoomAdded($dataHelper->booking, $roomBooking);

        return $roomBooking;
    }

    private function doUpdate(RoomBooking $currentRoomBooking, UpdateDataHelper $dataHelper): void
    {
        $this->makePipeline()->send($dataHelper);
        if (!$currentRoomBooking->details()->isEqual($dataHelper->details)) {
            $currentRoomBooking->setDetails($dataHelper->details);
        }
        $this->roomBookingRepository->store($currentRoomBooking);
        $this->events[] = new RoomEdited($dataHelper->booking, $currentRoomBooking);
    }

    private function doReplace(RoomBooking $currentRoomBooking, UpdateDataHelper $dataHelper): void
    {
        $hotelRoomDto = $this->hotelRoomAdapter->findById($dataHelper->roomInfo->id());
        $this->roomBookingRepository->delete($currentRoomBooking->id()->value());
        $this->events[] = new RoomDeleted($dataHelper->booking, $currentRoomBooking->id());
        $roomBooking = $this->doAdd($dataHelper);
        //@todo кинуть ексепшн если кол-во гостей больше доступного
        $maxGuestCount = $hotelRoomDto->guestsCount;
        foreach ($dataHelper->guestIds as $index => $guestId) {
            $guestNumber = $index + 1;
            if ($guestNumber > $maxGuestCount) {
                break;
            }
            $this->bookingGuestRepository->bind($roomBooking->id(), $guestId);
        }
    }

    private function processQuota(HotelBooking $booking): void
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
            ->through(ClientResidencyValidator::class)
            ->through(ExistRoomPriceValidator::class);
    }

    private function doAction(\Closure $action): void
    {
        $this->executor->execute($action);
        $this->eventDispatcher->dispatch(...$this->events);
    }
}
