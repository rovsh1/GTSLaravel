<?php

declare(strict_types=1);

namespace Module\Supplier\Payment\Domain\Payment\Listener;

use Module\Supplier\Payment\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Supplier\Payment\Domain\Payment\Repository\PaymentRepositoryInterface;
use Module\Supplier\Payment\Domain\Payment\ValueObject\Landing;
use Module\Supplier\Payment\Domain\Payment\ValueObject\LandingCollection;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Booking\IntegrationEvent\BookingCancelled;
use Sdk\Booking\IntegrationEvent\SupplierPriceChanged;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Shared\Contracts\Event\IntegrationEventListenerInterface;
use Sdk\Shared\Event\IntegrationEventMessage;

class UpdatePaymentLandingsListener implements IntegrationEventListenerInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly PaymentRepositoryInterface $paymentRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    public function handle(IntegrationEventMessage $message): void
    {
        $event = $message->event;
        assert($event instanceof BookingCancelled || $event instanceof SupplierPriceChanged);

        if ($event instanceof SupplierPriceChanged && $event->before->penaltyValue === $event->after->penaltyValue) {
            return;
        }

        $booking = $this->bookingRepository->findOrFail(new BookingId($event->bookingId));
        $payments = $this->paymentRepository->findByBookingId($booking->id());

        foreach ($payments as $payment) {
            $landings = array_filter(
                $payment->landings()->all(),
                fn(Landing $landing) => !$landing->bookingId()->isEqual($booking->id())
            );

            if ($booking->status() === StatusEnum::CANCELLED_FEE) {
                $landings[] = new Landing($booking->id(), $booking->supplierPenalty()->value());
            }

            $payment->setLandings(new LandingCollection($landings));
            $this->paymentRepository->store($payment);
            $this->eventDispatcher->dispatch(...$payment->pullEvents());
        }
    }
}
