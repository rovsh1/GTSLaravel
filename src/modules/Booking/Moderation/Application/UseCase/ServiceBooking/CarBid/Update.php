<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\ServiceBooking\CarBid;

use Module\Booking\Moderation\Application\Dto\CarBidDataDto;
use Module\Booking\Moderation\Application\Service\CarBidFactory;
use Module\Booking\Shared\Domain\Booking\DbContext\CarBidDbContextInterface;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Sdk\Booking\Entity\CarBid;
use Sdk\Booking\Event\TransferBooking\CarBidReplaced;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\CarBidId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Update implements UseCaseInterface
{
    public function __construct(
        private readonly CarBidFactory $carBidFactory,
        private readonly CarBidDbContextInterface $carBidDbContext,
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function execute(int $bookingId, int $carBidId, CarBidDataDto $carData): void
    {
        $booking = $this->bookingUnitOfWork->findOrFail(new BookingId($bookingId));
        $currentCarBid = $this->carBidDbContext->findOrFail(new CarBidId($carBidId));

        $this->carBidFactory->fromRequest($carData);

        $this->bookingUnitOfWork->touch($booking->id());

        if ($currentCarBid->carId()->value() === $carBidId) {
            $this->doUpdate($currentCarBid);
        } else {
            $this->doReplace($currentCarBid);
        }

        $this->bookingUnitOfWork->commit();
    }

    private function doUpdate(CarBid $currentCarBid): void
    {
        $isChanged = true;
        if (!$isChanged) {
            return;
        }
        $this->bookingUnitOfWork->persist($currentCarBid);
    }

    private function doReplace(CarBid $beforeCarBid): void
    {
        $this->bookingUnitOfWork->commiting(function () use ($beforeCarBid) {
            $this->carBidDbContext->delete($beforeCarBid->id());
            $carBid = $this->carBidFactory->create($beforeCarBid->bookingId());
            $details = $this->bookingUnitOfWork->getDetails($beforeCarBid->bookingId());
            $this->eventDispatcher->dispatch(new CarBidReplaced($details, $beforeCarBid, $carBid));
        });
    }
}
