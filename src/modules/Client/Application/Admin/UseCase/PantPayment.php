<?php

declare(strict_types=1);

namespace Module\Client\Application\Admin\UseCase;

use Module\Client\Application\Admin\Request\PantPaymentRequestDto;
use Module\Client\Domain\Invoice\Repository\InvoiceRepositoryInterface;
use Module\Client\Domain\Invoice\ValueObject\InvoiceId;
use Module\Client\Domain\Order\ValueObject\OrderId;
use Module\Client\Domain\Payment\Repository\PaymentRepositoryInterface;
use Module\Client\Domain\Payment\Repository\PlantRepositoryInterface;
use Module\Client\Domain\Payment\ValueObject\PaymentId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class PantPayment implements UseCaseInterface
{
    public function __construct(
        private readonly PaymentRepositoryInterface $paymentRepository,
        private readonly InvoiceRepositoryInterface $invoiceRepository,
        private readonly PlantRepositoryInterface $plantRepository,
    ) {
    }

    public function execute(PantPaymentRequestDto $requestDto): void
    {
        $payment = $this->paymentRepository->findOrFail(new PaymentId($requestDto->paymentId));
        $invoice = $this->invoiceRepository->findByOrderId(new OrderId($requestDto->orderId))
            ?? throw new \Exception('Actual invoice not found');

        $payment->addPlantSum($requestDto->sum);

        $this->plantRepository->create(
            paymentId: $payment->id(),
            invoiceId: new InvoiceId($invoice->id()->value()),
            orderId: new OrderId($requestDto->orderId),
            sum: $requestDto->sum
        );

        $this->paymentRepository->store($payment);
    }
}
