<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Application\Factory;

use Module\Client\Invoicing\Application\Dto\OrderDto;
use Module\Client\Invoicing\Domain\Order\Order;
use Sdk\Shared\Contracts\Service\TranslatorInterface;
use Sdk\Shared\Dto\CurrencyDto;
use Sdk\Shared\Dto\MoneyDto;

class OrderDtoFactory
{
    public function __construct(
        private readonly TranslatorInterface $translator
    ) {}

    public function build(Order $order): OrderDto
    {
        $remainingAmount = $order->clientPrice()->value() - $order->payedAmount()->value();

        $penalty = null;
        if ($order->clientPenalty() !== null) {
            $penalty = new MoneyDto(
                CurrencyDto::fromEnum($order->clientPrice()->currency(), $this->translator),
                $order->clientPenalty()->value(),
            );
        }

        return new OrderDto(
            id: $order->id()->value(),
            clientId: $order->clientId()->value(),
            clientPrice: new MoneyDto(
                CurrencyDto::fromEnum($order->clientPrice()->currency(), $this->translator),
                $order->clientPrice()->value(),
            ),
            clientPenalty: $penalty,
            payedAmount: new MoneyDto(
                CurrencyDto::fromEnum($order->payedAmount()->currency(), $this->translator),
                $order->payedAmount()->value(),
            ),
            remainingAmount: new MoneyDto(
                CurrencyDto::fromEnum($order->payedAmount()->currency(), $this->translator),
                $remainingAmount,
            ),
        );
    }

    /**
     * @param Order[] $orders
     * @return OrderDto[]
     */
    public function collection(array $orders): array
    {
        return array_map(fn(Order $order) => $this->build($order), $orders);
    }
}
