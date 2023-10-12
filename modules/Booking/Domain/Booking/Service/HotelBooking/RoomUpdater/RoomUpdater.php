<?php

namespace Module\Booking\Domain\Booking\Service\HotelBooking\RoomUpdater;

use Module\Booking\Deprecated\HotelBooking\Event\RoomDeleted;
use Module\Booking\Domain\Booking\Adapter\HotelRoomAdapterInterface;
use Module\Booking\Domain\Booking\Booking;
use Module\Booking\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Domain\Booking\Entity\HotelRoomBooking;
use Module\Booking\Domain\Booking\Exception\HotelBooking\InvalidRoomResidency;
use Module\Booking\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\Details\HotelBookingRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\HotelBooking\BookingGuestRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Domain\Booking\Service\HotelBooking\QuotaManager\Exception\ClosedRoomDateQuota;
use Module\Booking\Domain\Booking\Service\HotelBooking\QuotaManager\Exception\NotEnoughRoomDateQuota;
use Module\Booking\Domain\Booking\Service\HotelBooking\QuotaManager\Exception\NotFoundRoomDateQuota;
use Module\Booking\Domain\Booking\Service\HotelBooking\QuotaManager\QuotaManager;
use Module\Booking\Domain\Booking\Service\HotelBooking\RoomUpdater\Validator\ClientResidencyValidator;
use Module\Booking\Domain\Booking\Service\HotelBooking\RoomUpdater\Validator\ExistRoomPriceValidator;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingId;
use Module\Shared\Contracts\Service\SafeExecutorInterface;
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
        private readonly HotelBookingRepositoryInterface $detailsRepository,
        private readonly SafeExecutorInterface $executor,
        private readonly QuotaManager $quotaManager
    ) {
    }

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
        $currentRoomBooking = $this->roomBookingRepository->find($roomBookingId);
        $action = function () use ($currentRoomBooking, $dataHelper): void {
            if ($currentRoomBooking->roomInfo()->id() !== $dataHelper->roomInfo->id()) {
                $this->doReplace($currentRoomBooking, $dataHelper);

                return;
            }

            $this->doUpdate($currentRoomBooking, $dataHelper);
        };
        $this->doAction($action);
    }

    public function delete(Booking $booking, HotelBooking $details, RoomBookingId $roomBookingId): void
    {
        $this->doAction(function () use ($booking, $details, $roomBookingId) {
            $this->roomBookingRepository->delete($roomBookingId);
            $details->removeRoomBooking($roomBookingId);
            $this->processQuota($booking, $details);
            $this->events[] = new RoomDeleted($booking, $roomBookingId);
        });
    }

    private function doAdd(UpdateDataHelper $dataHelper): HotelRoomBooking
    {
        $this->makePipeline()->send($dataHelper);
        $roomBooking = $this->roomBookingRepository->create(
            $dataHelper->booking->id(),
            $dataHelper->roomInfo,
            $dataHelper->roomDetails,
            $dataHelper->price
        );
        $dataHelper->bookingDetails->addRoomBooking($roomBooking->id());
        $this->detailsRepository->store($dataHelper->bookingDetails);
        $this->processQuota($dataHelper->booking, $dataHelper->bookingDetails);
        //@todo заменить processQuota на ивент RoomAdding (который процессит по листенеру)
//        $this->events[] = new RoomAdded($dataHelper->booking, $roomBooking);

        return $roomBooking;
    }

    private function doUpdate(HotelRoomBooking $currentRoomBooking, UpdateDataHelper $dataHelper): void
    {
        $this->makePipeline()->send($dataHelper);
        if (!$currentRoomBooking->details()->isEqual($dataHelper->roomDetails)) {
            $currentRoomBooking->updateDetails($dataHelper->roomDetails);
        }
        $this->roomBookingRepository->store($currentRoomBooking);
//        $this->events[] = new RoomEdited($dataHelper->booking, $currentRoomBooking);
    }

    private function doReplace(HotelRoomBooking $currentRoomBooking, UpdateDataHelper $dataHelper): void
    {
        $hotelRoomDto = $this->hotelRoomAdapter->findById($dataHelper->roomInfo->id());
        $this->roomBookingRepository->delete($currentRoomBooking->id());
        $dataHelper->bookingDetails->removeRoomBooking($currentRoomBooking->id());
        $this->detailsRepository->store($dataHelper->bookingDetails);
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

    private function processQuota(Booking $booking, HotelBooking $details): void
    {
        $booking = $this->bookingRepository->find($booking->id());
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }
        $this->quotaManager->process($booking, $details);
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
