<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\Order;

use Module\Booking\Moderation\Application\Dto\Details\CancelConditionsDto;
use Module\Booking\Moderation\Application\Dto\ServiceBooking\ServiceInfoDto;
use Module\Booking\Moderation\Application\Dto\ServiceBooking\ServiceTypeDto;
use Module\Booking\Moderation\Application\Factory\BookingPriceDtoFactory;
use Module\Booking\Moderation\Application\ResponseDto\OrderBookingDto;
use Module\Booking\Moderation\Application\ResponseDto\OrderBookingPeriodDto;
use Module\Booking\Moderation\Application\ResponseDto\OrderBookingServiceInfoDto;
use Module\Booking\Shared\Application\Factory\BookingStatusDtoFactory;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Entity\CarRentWithDriver;
use Module\Booking\Shared\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Shared\Domain\Booking\Entity\ServiceDetailsInterface;
use Module\Booking\Shared\Domain\Booking\Factory\DetailsRepositoryFactory;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;
use Module\Shared\Contracts\Service\TranslatorInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetOrderBookings implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly BookingStatusDtoFactory $statusDtoFactory,
        private readonly BookingPriceDtoFactory $bookingPriceDtoFactory,
        private readonly DetailsRepositoryFactory $detailsRepositoryFactory,
        private readonly TranslatorInterface $translator
    ) {}

    public function execute(int $orderId): array
    {
        $bookings = $this->bookingRepository->getByOrderId(new OrderId($orderId));

        return $this->buildDTOs($bookings);
    }

    private function buildDTOs(array $bookings): array
    {
        return array_map(function (Booking $booking) {
            $repository = $this->detailsRepositoryFactory->build($booking);
            $details = $repository->find($booking->id());

            return new OrderBookingDto(
                id: $booking->id()->value(),
                status: $this->statusDtoFactory->get($booking->status()),
                orderId: $booking->orderId()->value(),
                createdAt: $booking->timestamps()->createdAt(),
                creatorId: $booking->context()->creatorId()->value(),
                prices: $this->bookingPriceDtoFactory->createFromEntity($booking->prices()),
                cancelConditions: $booking->cancelConditions() !== null
                    ? CancelConditionsDto::fromDomain($booking->cancelConditions())
                    : null,
                note: $booking->note(),
                serviceType: new ServiceTypeDto(
                    id: $booking->serviceType()->value,
                    name: $this->translator->translateEnum($booking->serviceType()),
                ),
                source: $booking->context()->source(),
                bookingPeriod: $this->buildBookingPeriodDTO($details),
                serviceInfo: $this->buildServiceInfoDTO($details),
            );
        }, $bookings);
    }

    private function buildBookingPeriodDTO(ServiceDetailsInterface $details): ?OrderBookingPeriodDto
    {
        $dateFrom = null;
        $dateTo = null;
        if ($details instanceof HotelBooking) {
            $dateFrom = $details->bookingPeriod()->dateFrom();
            $dateTo = $details->bookingPeriod()->dateTo();
        } elseif ($details instanceof CarRentWithDriver) {
            $dateFrom = $details->bookingPeriod()?->dateFrom();
            $dateTo = $details->bookingPeriod()?->dateTo();
        } elseif (method_exists($details, 'arrivalDate')) {
            $dateFrom = $details->arrivalDate();
            $dateTo = $details->arrivalDate();
        } elseif (method_exists($details, 'departureDate')) {
            $dateFrom = $details->departureDate();
            $dateTo = $details->departureDate();
        }

        if ($dateFrom === null && $dateTo === null) {
            return null;
        }

        return new OrderBookingPeriodDto($dateFrom, $dateTo);
    }

    private function buildServiceInfoDTO(ServiceDetailsInterface $details): OrderBookingServiceInfoDto
    {
        if ($details instanceof HotelBooking) {
            return new OrderBookingServiceInfoDto(
                $details->hotelInfo()->id(),
                'Отель: ' . $details->hotelInfo()->name(),
            );
        }

        return new OrderBookingServiceInfoDto(
            $details->serviceInfo()->id(),
            $details->serviceInfo()->title(),
        );
    }
}
