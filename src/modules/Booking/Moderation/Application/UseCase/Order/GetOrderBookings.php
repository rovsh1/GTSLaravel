<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\Order;

use Carbon\CarbonImmutable;
use Module\Booking\Moderation\Application\Dto\Details\CancelConditionsDto;
use Module\Booking\Moderation\Application\Dto\ServiceBooking\ServiceTypeDto;
use Module\Booking\Moderation\Application\Factory\BookingPriceDtoFactory;
use Module\Booking\Moderation\Application\Factory\BookingStatusDtoFactory;
use Module\Booking\Moderation\Application\ResponseDto\OrderBookingDto;
use Module\Booking\Moderation\Application\ResponseDto\OrderBookingPeriodDto;
use Module\Booking\Moderation\Application\ResponseDto\OrderBookingServiceInfoDto;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Sdk\Booking\Contracts\Entity\DetailsInterface;
use Sdk\Booking\Entity\Details\CarRentWithDriver;
use Sdk\Booking\Entity\Details\HotelBooking;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Contracts\Service\TranslatorInterface;

class GetOrderBookings implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly BookingStatusDtoFactory $statusDtoFactory,
        private readonly BookingPriceDtoFactory $bookingPriceDtoFactory,
        private readonly DetailsRepositoryInterface $detailsRepository,
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
            $details = $this->detailsRepository->findOrFail($booking->id());

            return new OrderBookingDto(
                id: $booking->id()->value(),
                status: $this->statusDtoFactory->build($booking->status()),
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

    private function buildBookingPeriodDTO(DetailsInterface $details): ?OrderBookingPeriodDto
    {
        $dateFrom = null;
        $dateTo = null;
        if ($details instanceof HotelBooking) {
            $dateFrom = $details->bookingPeriod()->dateFrom();
            $dateTo = $details->bookingPeriod()->dateTo();
        } elseif ($details instanceof CarRentWithDriver) {
            $dateFrom = $details->bookingPeriod()?->dateFrom();
            $dateTo = $details->bookingPeriod()?->dateTo();
        } elseif (method_exists($details, 'serviceDate')) {
            $dateFrom = $details->serviceDate();
            $dateTo = $details->serviceDate();
        }

        if ($dateFrom === null && $dateTo === null) {
            return null;
        }

        return new OrderBookingPeriodDto(
            new CarbonImmutable($dateFrom),
            new CarbonImmutable($dateTo)
        );
    }

    private function buildServiceInfoDTO(DetailsInterface $details): OrderBookingServiceInfoDto
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
