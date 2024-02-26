<?php

declare(strict_types=1);

namespace Module\Supplier\Payment\Application\UseCase;

use Module\Supplier\Payment\Application\Dto\PaymentDto;
use Module\Supplier\Payment\Domain\Payment\Repository\PaymentRepositoryInterface;
use Module\Supplier\Payment\Domain\Payment\ValueObject\Landing;
use Module\Supplier\Payment\Domain\Payment\ValueObject\PaymentId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Contracts\Service\TranslatorInterface;
use Sdk\Shared\Dto\CurrencyDto;
use Sdk\Shared\Dto\MoneyDto;

class FindPayment implements UseCaseInterface
{
    public function __construct(
        private readonly PaymentRepositoryInterface $paymentRepository,
        private readonly TranslatorInterface $translator
    ) {}

    public function execute(int $paymentId): ?PaymentDto
    {
        $payment = $this->paymentRepository->find(new PaymentId($paymentId));
        if ($payment === null) {
            return null;
        }

        return new PaymentDto(
            $payment->id()->value(),
            $payment->supplierId()->value(),
            new MoneyDto(
                CurrencyDto::fromEnum($payment->paymentAmount()->currency(), $this->translator),
                $payment->paymentAmount()->value()
            ),
            new MoneyDto(
                CurrencyDto::fromEnum($payment->paymentAmount()->currency(), $this->translator),
                $payment->lendedSum(),
            ),
            new MoneyDto(
                CurrencyDto::fromEnum($payment->paymentAmount()->currency(), $this->translator),
                ($payment->paymentAmount()->value() - $payment->lendedSum()),
            ),
            $payment->landings()->map(
                fn(Landing $landing) => ['bookingId' => $landing->bookingId()->value(), 'sum' => $landing->sum()]
            )
        );
    }
}
