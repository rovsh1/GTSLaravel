<?php

declare(strict_types=1);

namespace Module\Booking\Invoicing\Domain\Service\Factory;

use Module\Booking\Invoicing\Domain\Service\Dto\ServiceInfoDto;
use Module\Booking\Invoicing\Domain\Service\Factory\Details\BasicDetailsDataFactory;
use Module\Booking\Invoicing\Domain\Service\Factory\Details\CarBidDataFactory;
use Module\Booking\Invoicing\Domain\Service\Factory\Details\HotelAccommodationDataFactory;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\DbContext\CarBidDbContextInterface;
use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Sdk\Booking\Entity\Details\HotelBooking as HotelDetailsEntity;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Shared\Enum\ServiceTypeEnum;

class ServiceInfoDataFactory
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly AccommodationRepositoryInterface $accommodationRepository,
        private readonly HotelAccommodationDataFactory $hotelAccommodationDataFactory,
        private readonly CarBidDataFactory $carBidDataFactory,
        private readonly BasicDetailsDataFactory $basicDetailsDataFactory,
        private readonly CarBidDbContextInterface $carBidDbContext,
    ) {}

    /**
     * @param OrderId $orderId
     * @return ServiceInfoDto[]
     */
    public function build(OrderId $orderId): array
    {
        $bookings = $this->bookingRepository->getByOrderId($orderId);
        $confirmedBookings = array_filter($bookings, fn(Booking $booking) => $booking->isConfirmed());

        $services = [];
        foreach ($confirmedBookings as $booking) {
            $details = $this->detailsRepository->findOrFail($booking->id());
            if ($details instanceof HotelDetailsEntity) {
                $accommodations = $this->accommodationRepository->getByBookingId($booking->id());
                foreach ($accommodations as $accommodation) {
                    $services[] = $this->hotelAccommodationDataFactory->build($booking, $accommodation);
                }
            } elseif (in_array($booking->serviceType(), ServiceTypeEnum::getTransferCases())) {
                $carBids = $this->carBidDbContext->getByBookingId($booking->id());
                foreach ($carBids as $carBid) {
                    $services[] = $this->carBidDataFactory->build($booking, $carBid);
                }
            } else {
                $services[] = $this->basicDetailsDataFactory->build($booking, $details);
            }
        }

        return $services;
    }
}
