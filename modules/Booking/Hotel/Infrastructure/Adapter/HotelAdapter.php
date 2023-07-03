<?php

namespace Module\Booking\Hotel\Infrastructure\Adapter;

use Module\Booking\Hotel\Domain\Adapter\HotelAdapterInterface;
use Module\Hotel\Application\Response\HotelDto;
use Module\Hotel\Application\Response\MarkupSettingsDto;
use Module\Hotel\Application\UseCase\FindHotelById;
use Module\Hotel\Application\UseCase\GetMarkupSettings;

class HotelAdapter implements HotelAdapterInterface
{
    public function findById(int $id): ?HotelDto
    {
        return app(FindHotelById::class)->execute($id);
    }

    public function getMarkupSettings(int $id): MarkupSettingsDto
    {
        return app(GetMarkupSettings::class)->execute($id);
    }
}
