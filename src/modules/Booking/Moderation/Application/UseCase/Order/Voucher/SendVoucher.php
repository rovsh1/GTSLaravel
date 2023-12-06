<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\Order\Voucher;

use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SendVoucher implements UseCaseInterface
{
    public function __construct(
    ) {}

    public function execute(int $orderId): void
    {
        //@todo перед отправкой ваучера обязательно заполнить external number
        //@todo отправка ваучера по емейлу
    }
}
