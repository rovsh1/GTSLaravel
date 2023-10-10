<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\UseCase\Car;

use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Update implements UseCaseInterface
{
    public function execute(int $bookingId, array $carsData): void
    {
        //@todo провалидировать все машины из carsData
        //@todo создать объекты + коллекцию
        //@todo сохранить
    }
}
