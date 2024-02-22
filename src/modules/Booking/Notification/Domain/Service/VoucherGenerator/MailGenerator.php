<?php

declare(strict_types=1);

namespace Module\Booking\Notification\Domain\Service\VoucherGenerator;

use Module\Booking\Shared\Domain\Shared\Service\MailTemplateCompilerInterface;
use Sdk\Booking\ValueObject\OrderId;

class MailGenerator
{
    public function __construct(
        private readonly TemplateDataFactory $templateDataFactory,
        private readonly MailTemplateCompilerInterface $templateCompiler,
    ) {}

    public function generate(OrderId $orderId): string
    {
        $templateData = $this->templateDataFactory->build($orderId);

        return $this->templateCompiler->compile('order.voucher', $templateData);
    }
}
