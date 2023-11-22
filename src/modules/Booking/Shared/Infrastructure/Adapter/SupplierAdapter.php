<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Adapter;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Hotel\Pricing\Application\Dto\ServicePriceDto;
use Module\Shared\Enum\CurrencyEnum;
use Module\Supplier\Moderation\Application\Dto\AirportDto;
use Module\Supplier\Moderation\Application\Dto\CarDto;
use Module\Supplier\Moderation\Application\Response\CancelConditionsDto;
use Module\Supplier\Moderation\Application\Response\ServiceContractDto;
use Module\Supplier\Moderation\Application\Response\ServiceDto;
use Module\Supplier\Moderation\Application\Response\SupplierDto;
use Module\Supplier\Moderation\Application\UseCase\CancelConditions\GetServiceCancelConditions;
use Module\Supplier\Moderation\Application\UseCase\CancelConditions\GetCarCancelConditions;
use Module\Supplier\Moderation\Application\UseCase\Find;
use Module\Supplier\Moderation\Application\UseCase\FindAirport;
use Module\Supplier\Moderation\Application\UseCase\FindAirportServiceContract;
use Module\Supplier\Moderation\Application\UseCase\FindCar;
use Module\Supplier\Moderation\Application\UseCase\FindService;
use Module\Supplier\Moderation\Application\UseCase\FindTransferServiceContract;
use Module\Supplier\Moderation\Application\UseCase\GetAirportServicePrice;
use Module\Supplier\Moderation\Application\UseCase\GetCars;
use Module\Supplier\Moderation\Application\UseCase\GetOtherServicePrice;
use Module\Supplier\Moderation\Application\UseCase\GetTransferServicePrice;

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
        DateTimeInterface $date
    ): ?ServicePriceDto {
        return app(GetTransferServicePrice::class)->execute(
            $supplierId,
            $serviceId,
            $carId,
            $clientCurrency,
            Carbon::createFromInterface($date)
        );
    }

    public function findTransferServiceContract(int $serviceId): ?ServiceContractDto
    {
        return app(FindTransferServiceContract::class)->execute($serviceId);
    }

    public function getCarCancelConditions(int $serviceId, int $carId, \DateTimeInterface $date): ?CancelConditionsDto
    {
        return app(GetCarCancelConditions::class)->execute($serviceId, $carId, $date);
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

    public function getOtherServicePrice(
        int $supplierId,
        int $serviceId,
        CurrencyEnum $clientCurrency,
        CarbonInterface $date
    ): ?ServicePriceDto {
        return app(GetOtherServicePrice::class)->execute(
            $supplierId,
            $serviceId,
            $clientCurrency,
            $date
        );
    }

    public function getOtherCancelConditions(int $serviceId, DateTimeInterface $date): ?CancelConditionsDto
    {
        return app(GetServiceCancelConditions::class)->execute($serviceId, $date);
    }

    public function findAirportServiceContract(int $serviceId): ?ServiceContractDto
    {
        return app(FindAirportServiceContract::class)->execute($serviceId);
    }

    public function getAirportCancelConditions(int $serviceId, DateTimeInterface $date): ?CancelConditionsDto
    {
        return app(GetServiceCancelConditions::class)->execute($serviceId, $date);
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
