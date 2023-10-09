<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\ServiceBooking\Adapter;

use Carbon\CarbonInterface;
use Module\Booking\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Shared\Enum\CurrencyEnum;
use Module\Supplier\Application\Response\CancelConditionsDto;
use Module\Supplier\Application\Response\ServiceContractDto;
use Module\Supplier\Application\Response\ServiceDto;
use Module\Supplier\Application\Response\ServicePriceDto;
use Module\Supplier\Application\Response\SupplierDto;
use Module\Supplier\Application\UseCase\Find;
use Module\Supplier\Application\UseCase\FindAirportServiceContract;
use Module\Supplier\Application\UseCase\FindService;
use Module\Supplier\Application\UseCase\FindTransferServiceContract;
use Module\Supplier\Application\UseCase\GetAirportCancelConditions;
use Module\Supplier\Application\UseCase\GetAirportServicePrice;
use Module\Supplier\Application\UseCase\GetTransferCancelConditions;
use Module\Supplier\Application\UseCase\GetTransferServicePrice;

class SupplierAdapter implements SupplierAdapterInterface
{
    public function find(int $id): SupplierDto
    {
        return app(Find::class)->execute($id);
    }

    public function findService(int $id): ?ServiceDto
    {
        return app(FindService::class)->execute($id);
    }

    public function getTransferServicePrice(
        int $supplierId,
        int $serviceId,
        int $carId,
        CurrencyEnum $grossCurrency,
        CarbonInterface $date
    ): ?ServicePriceDto {
        return app(GetTransferServicePrice::class)->execute(
            $supplierId,
            $serviceId,
            $carId,
            $grossCurrency,
            $date
        );
    }

    public function findTransferServiceContract(int $serviceId): ?ServiceContractDto
    {
        return app(FindTransferServiceContract::class)->execute($serviceId);
    }

    public function getTransferCancelConditions(): CancelConditionsDto
    {
        return app(GetTransferCancelConditions::class)->execute();
    }

    public function getAirportServicePrice(
        int $supplierId,
        int $serviceId,
        int $airportId,
        CurrencyEnum $grossCurrency,
        CarbonInterface $date
    ): ?ServicePriceDto {
        return app(GetAirportServicePrice::class)->execute(
            $supplierId,
            $serviceId,
            $airportId,
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
