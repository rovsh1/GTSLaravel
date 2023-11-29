<?php

declare(strict_types=1);

namespace Module\Client\Payment\Application\UseCase;

use Module\Client\Payment\Application\Exception\InvalidOrderLandingSumDecimalsException;
use Module\Client\Payment\Application\Exception\OrdersLandingToPaymentInsufficientFundsException;
use Module\Client\Payment\Application\RequestDto\LendOrderToPaymentRequestDto;
use Module\Client\Payment\Domain\Payment\Exception\InvalidLandingSumDecimals;
use Module\Client\Payment\Domain\Payment\Exception\PaymentInsufficientFunds;
use Module\Client\Payment\Domain\Payment\Repository\PaymentRepositoryInterface;
use Module\Client\Payment\Domain\Payment\ValueObject\Landing;
use Module\Client\Payment\Domain\Payment\ValueObject\LandingCollection;
use Module\Client\Shared\Domain\ValueObject\OrderId;
use Module\Client\Shared\Domain\ValueObject\PaymentId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Exception\ApplicationException;

class OrdersLandingToPayment implements UseCaseInterface
{
    public function __construct(
        private readonly PaymentRepositoryInterface $paymentRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    /**
     * @param int $paymentId
     * @param LendOrderToPaymentRequestDto[] $orders
     * @return void
     * @throws ApplicationException
     */
    public function execute(int $paymentId, array $orders): void
    {
        $payment = $this->paymentRepository->findOrFail(new PaymentId($paymentId));
        $landings = array_map(
            fn(LendOrderToPaymentRequestDto $dto) => new Landing(new OrderId($dto->orderId), $dto->sum),
            $orders
        );
        $landings = new LandingCollection($landings);
        try {
            $payment->setLandings($landings);
            $this->paymentRepository->store($payment);
            $this->eventDispatcher->dispatch(...$payment->pullEvents());
        } catch (PaymentInsufficientFunds $e) {
            throw new OrdersLandingToPaymentInsufficientFundsException($e);
        } catch (InvalidLandingSumDecimals $e) {
            throw new InvalidOrderLandingSumDecimalsException($e);
        }
    }
}
