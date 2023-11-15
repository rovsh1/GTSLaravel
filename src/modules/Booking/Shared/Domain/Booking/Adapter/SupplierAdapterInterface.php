<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Adapter;

use Carbon\CarbonInterface;
use Module\Hotel\Pricing\Application\Dto\ServicePriceDto;
use Module\Shared\Enum\CurrencyEnum;
use Module\Supplier\Moderation\Application\Dto\AirportDto;
use Module\Supplier\Moderation\Application\Dto\CarDto;
use Module\Supplier\Moderation\Application\Response\CancelConditionsDto;
use Module\Supplier\Moderation\Application\Response\ServiceContractDto;
use Module\Supplier\Moderation\Application\Response\ServiceDto;
use Module\Supplier\Moderation\Application\Response\SupplierDto;

interface SupplierAdapterInterface
{
    public function find(int $id): SupplierDto;

    public function findService(int $id): ?ServiceDto;

    public function getTransferServicePrice(
        int $supplierId,
        int $serviceId,
        int $carId,
        CurrencyEnum $clientCurrency,
        CarbonInterface $date
    ): ?ServicePriceDto;

    public function findTransferServiceContract(int $serviceId): ?ServiceContractDto;

    public function getTransferCancelConditions(): CancelConditionsDto;

    public function getAirportServicePrice(
        int $supplierId,
        int $serviceId,
        CurrencyEnum $clientCurrency,
        CarbonInterface $date
    ): ?ServicePriceDto;

    public function findAirportServiceContract(int $serviceId): ?ServiceContractDto;

    public function getAirportCancelConditions(): CancelConditionsDto;

    /**
     * @param int $supplierId
     * @return CarDto[]
     */
    public function getSupplierCars(int $supplierId): array;

    public function findCar(int $carId): ?CarDto;

    public function findAirport(int $airportId): ?AirportDto;
}
