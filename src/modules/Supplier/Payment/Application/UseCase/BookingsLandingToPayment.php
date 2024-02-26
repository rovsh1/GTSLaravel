<?php

declare(strict_types=1);

namespace Module\Supplier\Payment\Application\UseCase;

use Module\Supplier\Payment\Application\Exception\InvalidBookingLandingSumDecimalsException;
use Module\Supplier\Payment\Application\Exception\BookingsLandingToPaymentInsufficientFundsException;
use Module\Supplier\Payment\Application\RequestDto\LendBookingToPaymentRequestDto;
use Module\Supplier\Payment\Domain\Payment\Exception\InvalidLandingSumDecimals;
use Module\Supplier\Payment\Domain\Payment\Exception\PaymentInsufficientFunds;
use Module\Supplier\Payment\Domain\Payment\Repository\PaymentRepositoryInterface;
use Module\Supplier\Payment\Domain\Payment\ValueObject\Landing;
use Module\Supplier\Payment\Domain\Payment\ValueObject\LandingCollection;
use Module\Supplier\Payment\Domain\Payment\ValueObject\PaymentId;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Exception\ApplicationException;

class BookingsLandingToPayment implements UseCaseInterface
{
    public function __construct(
        private readonly PaymentRepositoryInterface $paymentRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    /**
     * @param int $paymentId
     * @param LendBookingToPaymentRequestDto[] $bookings
     * @return void
     * @throws ApplicationException
     */
    public function execute(int $paymentId, array $bookings): void
    {
        $payment = $this->paymentRepository->findOrFail(new PaymentId($paymentId));
        $landings = array_map(
            fn(LendBookingToPaymentRequestDto $dto) => new Landing(new BookingId($dto->bookingId), $dto->sum),
            $bookings
        );
        $landings = new LandingCollection($landings);
        try {
            $payment->setLandings($landings);
            $this->paymentRepository->store($payment);
            $this->eventDispatcher->dispatch(...$payment->pullEvents());
        } catch (PaymentInsufficientFunds $e) {
            throw new BookingsLandingToPaymentInsufficientFundsException($e);
        } catch (InvalidLandingSumDecimals $e) {
            throw new InvalidBookingLandingSumDecimalsException($e);
        }
    }
}
