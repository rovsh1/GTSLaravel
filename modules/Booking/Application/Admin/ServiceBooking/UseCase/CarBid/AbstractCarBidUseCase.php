<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\UseCase\CarBid;

use Module\Booking\Application\Admin\ServiceBooking\Factory\DetailsRepositoryFactory;
use Module\Booking\Domain\Booking\Entity\Concerns\HasCarBidCollectionTrait;
use Module\Booking\Domain\Booking\Entity\ServiceDetailsInterface;
use Module\Booking\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;

abstract class AbstractCarBidUseCase
{
    private $detailsRepository;

    public function __construct(
        private readonly DetailsRepositoryFactory $detailsRepositoryFactory,
        private readonly BookingRepositoryInterface $bookingRepository
    ) {}

    protected function getBookingDetails(BookingId $bookingId): ServiceDetailsInterface
    {
        $repository = $this->getRepository($bookingId);

        $details = $repository->find($bookingId);
        if (!in_array(HasCarBidCollectionTrait::class, class_uses($details::class), true)) {
            throw new \RuntimeException('Details doesn\'t has car bids');
        }

        return $details;
    }

    protected function storeDetails(ServiceDetailsInterface $details): bool
    {
        return $this->getRepository($details->bookingId())->store($details);
    }

    private function getRepository(BookingId $bookingId): mixed
    {
        if ($this->detailsRepository === null) {
            $booking = $this->bookingRepository->findOrFail($bookingId);
            $this->detailsRepository = $this->detailsRepositoryFactory->build($booking);
        }

        return $this->detailsRepository;
    }
}
