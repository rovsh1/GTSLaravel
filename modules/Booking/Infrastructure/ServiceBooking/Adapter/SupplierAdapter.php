<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\ServiceBooking\Adapter;

use Carbon\CarbonInterface;
use Module\Booking\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Pricing\Application\Dto\ServicePriceDto;
use Module\Pricing\Application\UseCase\GetAirportServicePrice;
use Module\Pricing\Application\UseCase\GetTransferServicePrice;
use Module\Shared\Enum\CurrencyEnum;
use Module\Supplier\Application\Dto\AirportDto;
use Module\Supplier\Application\Dto\CarDto;
use Module\Supplier\Application\Response\CancelConditionsDto;
use Module\Supplier\Application\Response\ServiceContractDto;
use Module\Supplier\Application\Response\ServiceDto;
use Module\Supplier\Application\Response\SupplierDto;
use Module\Supplier\Application\UseCase\Find;
use Module\Supplier\Application\UseCase\FindAirport;
use Module\Supplier\Application\UseCase\FindAirportServiceContract;
use Module\Supplier\Application\UseCase\FindCar;
use Module\Supplier\Application\UseCase\FindService;
use Module\Supplier\Application\UseCase\FindTransferServiceContract;
use Module\Supplier\Application\UseCase\GetAirportCancelConditions;
use Module\Supplier\Application\UseCase\GetCars;
use Module\Supplier\Application\UseCase\GetTransferCancelConditions;

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
        CurrencyEnum $grossCurrency,
        CarbonInterface $date
    ): ?ServicePriceDto {
        return app(GetAirportServicePrice::class)->execute(
            $supplierId,
            $serviceId,
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

    /**
     * @param int $supplierId
     * @return CarDto[]
     */
    public function getSupplierCars(int $supplierId): array
    {
        return app(GetCars::class)->execute($supplierId);
    }

    public function findCar(int $carId): ?CarDto
    {
        return app(FindCar::class)->execute($carId);
    }

    public function findAirport(int $airportId): ?AirportDto
    {
        return app(FindAirport::class)->execute($airportId);
    }
}
