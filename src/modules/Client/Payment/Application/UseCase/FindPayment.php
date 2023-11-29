<?php

declare(strict_types=1);

namespace Module\Client\Payment\Application\UseCase;

use Module\Client\Payment\Application\Dto\PaymentDto;
use Module\Client\Payment\Domain\Payment\Repository\PaymentRepositoryInterface;
use Module\Client\Shared\Domain\ValueObject\PaymentId;
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
            $payment->clientId()->value(),
            $payment->paymentAmount()->methodId(),
            new MoneyDto(
                CurrencyDto::fromEnum($payment->paymentAmount()->currency(), $this->translator),
                $payment->paymentAmount()->sum()
            ),
            new MoneyDto(
                CurrencyDto::fromEnum($payment->paymentAmount()->currency(), $this->translator),
                $payment->lendedSum(),
            ),
            new MoneyDto(
                CurrencyDto::fromEnum($payment->paymentAmount()->currency(), $this->translator),
                ($payment->paymentAmount()->sum() - $payment->lendedSum()),
            ),
        );
    }
}
