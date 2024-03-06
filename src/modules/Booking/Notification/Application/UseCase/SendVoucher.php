<?php

declare(strict_types=1);

namespace Module\Booking\Notification\Application\UseCase;

use Module\Booking\Moderation\Domain\Order\ValueObject\Voucher;
use Module\Booking\Notification\Domain\Factory\VoucherFactory;
use Module\Booking\Notification\Domain\Service\VoucherGenerator\MailGenerator;
use Module\Booking\Shared\Domain\Order\DbContext\OrderDbContextInterface;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Shared\Domain\Shared\Adapter\ClientAdapterInterface;
use Module\Booking\Shared\Domain\Shared\Service\ClientLocaleContext;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;
use Sdk\Shared\Dto\Mail\AttachmentDto;
use Shared\Contracts\Adapter\MailAdapterInterface;

class SendVoucher implements UseCaseInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly OrderDbContextInterface $orderDbContext,
        private readonly VoucherFactory $voucherFactory,
        private readonly MailAdapterInterface $mailAdapter,
        private readonly ClientAdapterInterface $clientAdapter,
        private readonly ClientLocaleContext $clientLocaleDecorator,
        private readonly MailGenerator $mailGenerator,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function execute(int $orderId): void
    {
        $order = $this->orderRepository->findOrFail(new OrderId($orderId));
        $client = $this->clientAdapter->find($order->clientId()->value());
        if ($client === null) {
            throw new EntityNotFoundException('Client not found');
        }
        if (empty($client->email)) {
            throw new \RuntimeException('Не указан емейл клиента');
        }

        $voucher = $order->voucher();
        if ($order->timestamps()->updatedAt()->getTimestamp() > $voucher?->createdAt()->getTimestamp()) {
            //@todo всегда выполняется, т.к. ваучер записывается в заказ и поле updated_at обновляется
            $voucher = $this->voucherFactory->build($order);
        }

        $voucher = new Voucher($voucher->createdAt(), $voucher->file(), $voucher->wordFile(), now()->toDateTimeImmutable());
        $order->setVoucher($voucher);
        $this->orderDbContext->store($order);
        $this->eventDispatcher->dispatch(...$order->pullEvents());

        $body = $this->clientLocaleDecorator->executeInClientLocale(
            $order->clientId(),
            fn() => $this->mailGenerator->generate($order->id())
        );
        $this->mailAdapter->sendTo(
            $client->email,
            'Ваучер',
            $body,
            [new AttachmentDto($voucher->file()->guid())]
        );
    }
}
