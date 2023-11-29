<?php

declare(strict_types=1);

namespace Module\Client\Payment\Application\UseCase;

use Module\Client\Payment\Application\Exception\LendOrderToPaymentInsufficientFundsException;
use Module\Client\Payment\Application\RequestDto\LendOrderToPaymentRequestDto;
use Module\Client\Payment\Domain\Payment\Exception\PaymentInsufficientFunds;
use Module\Client\Payment\Domain\Payment\Repository\PaymentRepositoryInterface;
use Module\Client\Payment\Domain\Payment\ValueObject\Landing;
use Module\Client\Payment\Domain\Payment\ValueObject\LandingCollection;
use Module\Client\Shared\Domain\ValueObject\OrderId;
use Module\Client\Shared\Domain\ValueObject\PaymentId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

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
     * @throws \Exception
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
            throw new LendOrderToPaymentInsufficientFundsException($e);
        }
    }
}
