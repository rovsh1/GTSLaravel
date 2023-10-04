<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\AirportBooking\Adapter;

use Carbon\CarbonInterface;
use Module\Booking\Domain\AirportBooking\Adapter\SupplierAdapterInterface;
use Module\Shared\Enum\CurrencyEnum;
use Module\Supplier\Application\Response\CancelConditionsDto;
use Module\Supplier\Application\Response\ServiceContractDto;
use Module\Supplier\Application\Response\ServicePriceDto;
use Module\Supplier\Application\Response\SupplierDto;
use Module\Supplier\Application\UseCase\Find;
use Module\Supplier\Application\UseCase\FindAirportServiceContract;
use Module\Supplier\Application\UseCase\GetAirportCancelConditions;
use Module\Supplier\Application\UseCase\GetAirportServicePrice;

class SupplierAdapter implements SupplierAdapterInterface
{
    public function find(int $id): SupplierDto
    {
        return app(Find::class)->execute($id);
    }

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

    public function findAirportServiceContract(int $serviceId): ?ServiceContractDto
    {
        return app(FindAirportServiceContract::class)->execute($serviceId);
    }

    public function getAirportCancelConditions(): CancelConditionsDto
    {
        return app(GetAirportCancelConditions::class)->execute();
    }
}
