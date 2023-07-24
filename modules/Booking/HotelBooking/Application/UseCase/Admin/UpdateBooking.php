<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\UseCase\Admin;

use Module\Booking\Common\Domain\Service\BookingUpdater;
use Module\Booking\HotelBooking\Application\Factory\CancelConditionsFactory;
use Module\Booking\HotelBooking\Application\Request\UpdateBookingDto;
use Module\Booking\HotelBooking\Domain\Adapter\HotelAdapterInterface;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\BookingPeriod;
use Module\Booking\Order\Domain\Repository\OrderRepositoryInterface;
use Module\Booking\Order\Domain\Service\OrderUpdater;
use Module\Shared\Domain\ValueObject\Id;
use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateBooking implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly BookingUpdater $bookingUpdater,
        private readonly HotelAdapterInterface $hotelAdapter,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly OrderUpdater $orderUpdater,
    ) {}

    public function execute(UpdateBookingDto $request): void
    {
        /** @var Booking $booking */
        $booking = $this->repository->find($request->id);
        $order = $this->orderRepository->find($booking->orderId()->value());

        if ($order->clientId()->value() !== $request->clientId) {
            $order->setClientId(
                new Id($request->clientId)
            );
        }
        $currencyFromRequest = CurrencyEnum::fromId($request->currencyId);
        if ($order->currency()->value !== $currencyFromRequest->value) {
            $order->setCurrency($currencyFromRequest);
        }
        if ($order->legalId()?->value() !== $request->legalId) {
            $order->setLegalId(
                new Id($request->legalId)
            );
        }
        $this->orderUpdater->store($order);

        $periodFromRequest = BookingPeriod::fromCarbon($request->period);
        if (!$booking->period()->isEqual($periodFromRequest)) {
            $markupSettings = $this->hotelAdapter->getMarkupSettings($booking->hotelInfo()->id());
            $cancelConditions = CancelConditionsFactory::fromDto($markupSettings->cancelPeriods, $request->period);
            $booking->setPeriod($periodFromRequest);
            $booking->setCancelConditions($cancelConditions);
        }

        if ($booking->note() !== $request->note) {
            $booking->setNote($request->note);
        }

        $this->bookingUpdater->store($booking);
    }

}
