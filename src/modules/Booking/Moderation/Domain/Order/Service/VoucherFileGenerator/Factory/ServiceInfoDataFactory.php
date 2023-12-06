<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\Factory;

use Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\Factory\Details\BasicDetailsDataFactory;
use Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\Factory\Details\CarBidDataFactory;
use Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\Factory\Details\HotelAccommodationDataFactory;
use Module\Booking\Shared\Domain\Booking\Booking;
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
    ) {}

    /**
     * @param OrderId $orderId
     * @return ServiceInfoDto[]
     */
    public function build(OrderId $orderId): array
    {
        $bookings = $this->bookingRepository->getByOrderId($orderId);
        $confirmedBookings = array_filter($bookings, fn(Booking $booking) => $booking->isConfirmed());//@todo DRY вынести отдельно

        $services = [];
        foreach ($confirmedBookings as $booking) {
            $details = $this->detailsRepository->findOrFail($booking->id());
            if ($details instanceof HotelDetailsEntity) {
                $accommodations = $this->accommodationRepository->getByBookingId($booking->id());
                foreach ($accommodations as $accommodation) {
                    $services[] = $this->hotelAccommodationDataFactory->build($booking, $accommodation);
                }
            } elseif (in_array($booking->serviceType(), ServiceTypeEnum::getTransferCases())) {
                foreach ($details->carBids() as $carBid) {
                    $services[] = $this->carBidDataFactory->build($booking, $carBid);
                }
            } else {
                $services[] = $this->basicDetailsDataFactory->build($booking, $details);
            }
        }

        return $services;
    }
}
