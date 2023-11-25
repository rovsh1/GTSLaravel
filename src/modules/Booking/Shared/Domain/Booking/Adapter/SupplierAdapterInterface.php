<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Adapter;

use Carbon\CarbonInterface;
use DateTimeInterface;
use Module\Hotel\Pricing\Application\Dto\ServicePriceDto;
use Module\Supplier\Moderation\Application\Dto\AirportDto;
use Module\Supplier\Moderation\Application\Dto\CarDto;
use Module\Supplier\Moderation\Application\Response\CancelConditionsDto;
use Module\Supplier\Moderation\Application\Response\ServiceContractDto;
use Module\Supplier\Moderation\Application\Response\ServiceDto;
use Module\Supplier\Moderation\Application\Response\SupplierDto;
use Sdk\Shared\Enum\CurrencyEnum;

interface SupplierAdapterInterface
{
    public function find(int $id): SupplierDto;

    public function findService(int $id): ?ServiceDto;

    public function getTransferServicePrice(
        int $supplierId,
        int $serviceId,
        int $carId,
        CurrencyEnum $clientCurrency,
        DateTimeInterface $date
    ): ?ServicePriceDto;

    public function findTransferServiceContract(int $serviceId): ?ServiceContractDto;

    public function getCarCancelConditions(int $serviceId, int $carId, \DateTimeInterface $date): ?CancelConditionsDto;

    public function getAirportServicePrice(
        int $supplierId,
        int $serviceId,
        CurrencyEnum $clientCurrency,
        CarbonInterface $date
    ): ?ServicePriceDto;

    public function getOtherServicePrice(
        int $supplierId,
        int $serviceId,
        CurrencyEnum $clientCurrency,
        CarbonInterface $date
    ): ?ServicePriceDto;

    public function getOtherCancelConditions(int $serviceId, DateTimeInterface $date): ?CancelConditionsDto;

    public function findAirportServiceContract(int $serviceId): ?ServiceContractDto;

    public function getAirportCancelConditions(int $serviceId, DateTimeInterface $date): ?CancelConditionsDto;

    /**
     * @param int $supplierId
     * @return CarDto[]
     */
    public function getSupplierCars(int $supplierId): array;

    public function findCar(int $carId): ?CarDto;

    public function findAirport(int $airportId): ?AirportDto;
}
