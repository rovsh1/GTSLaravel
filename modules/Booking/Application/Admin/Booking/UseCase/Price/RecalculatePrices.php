<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\Booking\UseCase\Price;

use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class RecalculatePrices implements UseCaseInterface
{
    public function __construct() {}

    public function execute(int $bookingId): void {
        //@todo на фронте должна быть кнопка "пересчитать", она дергает пересчет
        //@todo все кейсы set - просто устанавливают цену и не пересчитывают
        //@todo есть 1 листенер который вызывает пересчет

        //@todo изменение цен в комнатах вызывает пересчет
    }
}
