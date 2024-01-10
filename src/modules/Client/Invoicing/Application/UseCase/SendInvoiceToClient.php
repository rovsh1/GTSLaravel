<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Application\UseCase;

use Module\Client\Invoicing\Application\Exception\InvoiceNotFoundException;
use Module\Client\Invoicing\Domain\Invoice\Adapter\MailGeneratorAdapterInterface;
use Module\Client\Invoicing\Domain\Invoice\Repository\InvoiceRepositoryInterface;
use Module\Client\Invoicing\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Client\Shared\Domain\Repository\ClientRequisitesRepositoryInterface;
use Module\Client\Shared\Domain\ValueObject\OrderId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Contracts\Adapter\MailAdapterInterface;
use Sdk\Shared\Dto\Mail\AttachmentDto;

class SendInvoiceToClient implements UseCaseInterface
{
    public function __construct(
        private readonly InvoiceRepositoryInterface $invoiceRepository,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly MailAdapterInterface $mailAdapter,
        private readonly ClientRequisitesRepositoryInterface $clientRequisitesRepository,
        private readonly MailGeneratorAdapterInterface $mailGeneratorAdapter,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function execute(int $orderId): void
    {
        $order = $this->orderRepository->findOrFail(new OrderId($orderId));
        $invoice = $this->invoiceRepository->findByOrderId($order->id());
        if ($invoice === null) {
            throw new InvoiceNotFoundException();
        }
        $clientEmail = $this->clientRequisitesRepository->getEmail($order->clientId());
        if (empty($clientEmail)) {
            throw new \RuntimeException('Не указан емейл клиента');
        }

        $invoice->send();
        $this->invoiceRepository->store($invoice);
        $this->eventDispatcher->dispatch(...$invoice->pullEvents());

        $body = $this->mailGeneratorAdapter->generate($order->id());
        $this->mailAdapter->sendTo(
            $clientEmail,
            'Инвойс',
            $body,
            [new AttachmentDto($invoice->document()->guid())]
        );
    }
}
