<?php

namespace Module\Booking\Hotel\Infrastructure\Adapter;

use Module\Booking\Hotel\Domain\Adapter\HotelAdapterInterface;
use Module\Hotel\Application\Dto\MarkupSettingsDto;
use Module\Hotel\Application\UseCase\GetHotelById;
use Module\Hotel\Application\UseCase\GetMarkupSettings;
use Module\Shared\Infrastructure\Adapter\AbstractModuleAdapter;

class HotelAdapter extends AbstractModuleAdapter implements HotelAdapterInterface
{
    public function findById(int $id): mixed
    {
        return app(GetHotelById::class)->execute($id);
    }

    public function getMarkupSettings(int $id): MarkupSettingsDto
    {
        return app(GetMarkupSettings::class)->execute($id);
    }

    protected function getModuleKey(): string
    {
        return 'Hotel';
    }
}
