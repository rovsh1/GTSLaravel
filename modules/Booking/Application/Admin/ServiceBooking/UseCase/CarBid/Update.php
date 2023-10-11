<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\UseCase\CarBid;

use Module\Booking\Application\Admin\ServiceBooking\Dto\CarBidDataDto;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Update implements UseCaseInterface
{
    public function execute(int $bookingId, string $carBidId, CarBidDataDto $carData): void
    {
        //@todo провалидировать все машины из carsData
        //@todo создать объекты + коллекцию
        //@todo сохранить
    }
}
