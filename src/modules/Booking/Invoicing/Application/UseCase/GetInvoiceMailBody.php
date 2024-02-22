<?php

declare(strict_types=1);

namespace Module\Booking\Invoicing\Application\UseCase;

use Module\Booking\Invoicing\Domain\Service\MailGenerator;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Shared\Domain\Shared\Service\ClientLocaleContext;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetInvoiceMailBody implements UseCaseInterface
{
    public function __construct(
        private readonly MailGenerator $fileGenerator,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly ClientLocaleContext $clientLocaleDecorator,
    ) {}

    public function execute(int $orderId): string
    {
        $order = $this->orderRepository->findOrFail(new OrderId($orderId));

        return $this->clientLocaleDecorator->executeInClientLocale(
            $order->clientId(),
            fn() => $this->fileGenerator->generate($order->id())
        );
    }
}
