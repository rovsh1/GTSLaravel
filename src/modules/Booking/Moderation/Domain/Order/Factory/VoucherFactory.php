<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Order\Factory;


use Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\FileGenerator;
use Module\Booking\Moderation\Domain\Order\ValueObject\Voucher;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Shared\ValueObject\File;

class VoucherFactory
{
    public function __construct(
        private readonly FileGenerator $fileGenerator
    ) {}

    public function build(OrderId $orderId): Voucher
    {
        $fileDto = $this->fileGenerator->generate($this->getFilename($orderId), $orderId);

        return new Voucher(
            now()->toDateTimeImmutable(),
            new File($fileDto->guid),
        );
    }

    private function getFilename(OrderId $orderId): string
    {
        return 'voucher-' . $orderId->value() . '-' . date('Ymd') . '.pdf';
    }
}
