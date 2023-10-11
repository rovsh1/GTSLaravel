<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\UseCase\CarBid;

use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Remove implements UseCaseInterface
{
    public function execute(int $bookingId, string $carBidId): void
    {
        //@todo провалидировать все машины из carsData
        //@todo создать объекты + коллекцию
        //@todo сохранить
    }
}
