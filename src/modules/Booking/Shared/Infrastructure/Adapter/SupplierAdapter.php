<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Adapter;

use Carbon\CarbonInterface;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Hotel\Pricing\Application\Dto\ServicePriceDto;
use Module\Hotel\Pricing\Application\UseCase\GetAirportServicePrice;
use Module\Hotel\Pricing\Application\UseCase\GetTransferServicePrice;
use Module\Shared\Enum\CurrencyEnum;
use Module\Supplier\Moderation\Application\Dto\AirportDto;
use Module\Supplier\Moderation\Application\Dto\CarDto;
use Module\Supplier\Moderation\Application\Response\CancelConditionsDto;
use Module\Supplier\Moderation\Application\Response\ServiceContractDto;
use Module\Supplier\Moderation\Application\Response\ServiceDto;
use Module\Supplier\Moderation\Application\Response\SupplierDto;
use Module\Supplier\Moderation\Application\UseCase\CancelConditions\GetAirportCancelConditions;
use Module\Supplier\Moderation\Application\UseCase\CancelConditions\GetTransferCancelConditions;
use Module\Supplier\Moderation\Application\UseCase\Find;
use Module\Supplier\Moderation\Application\UseCase\FindAirport;
use Module\Supplier\Moderation\Application\UseCase\FindAirportServiceContract;
use Module\Supplier\Moderation\Application\UseCase\FindCar;
use Module\Supplier\Moderation\Application\UseCase\FindService;
use Module\Supplier\Moderation\Application\UseCase\FindTransferServiceContract;
use Module\Supplier\Moderation\Application\UseCase\GetCars;

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
        CurrencyEnum $clientCurrency,
        CarbonInterface $date
    ): ?ServicePriceDto {
        return app(GetTransferServicePrice::class)->execute(
            $supplierId,
            $serviceId,
            $carId,
            $clientCurrency,
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
        CurrencyEnum $clientCurrency,
        CarbonInterface $date
    ): ?ServicePriceDto {
        return app(GetAirportServicePrice::class)->execute(
            $supplierId,
            $serviceId,
            $clientCurrency,
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
