<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Infrastructure\Adapter;

use Carbon\CarbonInterface;
use Module\Booking\Airport\Domain\Adapter\SupplierAdapterInterface;
use Module\Shared\Enum\CurrencyEnum;
use Module\Supplier\Application\Response\ServicePriceDto;
use Module\Supplier\Application\Response\SupplierDto;
use Module\Supplier\Application\UseCase\Find;
use Module\Supplier\Application\UseCase\GetAirportServicePrice;

class SupplierAdapter implements SupplierAdapterInterface
{
    public function getAirportServicePrice(
        int $supplierId,
        int $serviceId,
        int $airportId,
        CurrencyEnum $netCurrency,
        CurrencyEnum $grossCurrency,
        CarbonInterface $date
    ): ?ServicePriceDto {
        return app(GetAirportServicePrice::class)->execute(
            $supplierId,
            $serviceId,
            $airportId,
            $netCurrency,
            $grossCurrency,
            $date
        );
    }

    public function find(int $id): SupplierDto
    {
        return app(Find::class)->execute($id);
    }
}
