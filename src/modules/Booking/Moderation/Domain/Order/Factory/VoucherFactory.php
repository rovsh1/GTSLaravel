<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Order\Factory;


use Module\Booking\Moderation\Domain\Order\Service\VoucherGenerator\FileGenerator;
use Module\Booking\Moderation\Domain\Order\ValueObject\Voucher;
use Module\Booking\Shared\Domain\Order\Order;
use Module\Booking\Shared\Domain\Shared\Service\ClientLocaleContext;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Shared\ValueObject\File;

class VoucherFactory
{
    public function __construct(
        private readonly FileGenerator $fileGenerator,
        private readonly ClientLocaleContext $clientLocaleDecorator,
    ) {}

    public function build(Order $order): Voucher
    {
        $orderId = $order->id();
        $fileDto = $this->clientLocaleDecorator->executeInClientLocale(
            $order->clientId(),
            fn() => $this->fileGenerator->generate($this->getFilename($orderId), $orderId)
        );

        return new Voucher(
            now()->toDateTimeImmutable(),
            new File($fileDto->guid),
            $order->voucher()?->sendAt()
        );
    }

    private function getFilename(OrderId $orderId): string
    {
        return 'voucher-' . $orderId->value() . '-' . date('Ymd') . '.pdf';
    }
}
