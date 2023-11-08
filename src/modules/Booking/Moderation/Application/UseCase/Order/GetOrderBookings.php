<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\Order;

use Module\Booking\Moderation\Application\Dto\Details\CancelConditionsDto;
use Module\Booking\Moderation\Application\Dto\ServiceBooking\BookingDto;
use Module\Booking\Moderation\Application\Dto\ServiceBooking\ServiceTypeDto;
use Module\Booking\Moderation\Application\Factory\BookingPriceDtoFactory;
use Module\Booking\Moderation\Application\Factory\BookingStatusDtoFactory;
use Module\Booking\Shared\Domain\Booking\Booking;
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
        private readonly TranslatorInterface $translator
    ) {}

    public function execute(int $orderId): array
    {
        $bookings = $this->bookingRepository->getByOrderId(new OrderId($orderId));

        return $this->buildDTOs($bookings);
    }

    private function buildDTOs(array $bookings): array
    {
        return array_map(fn(Booking $booking) => new BookingDto(
            id: $booking->id()->value(),
            status: $this->statusDtoFactory->get($booking->status()),
            orderId: $booking->orderId()->value(),
            createdAt: $booking->timestamps()->createdDate(),
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
            details: null,
            source: $booking->context()->source(),
        ), $bookings);
    }
}
