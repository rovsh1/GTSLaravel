<?php

declare(strict_types=1);

namespace Module\Booking\Domain\ServiceBooking\Adapter;

use Carbon\CarbonInterface;
use Module\Shared\Enum\CurrencyEnum;
use Module\Supplier\Application\Response\CancelConditionsDto;
use Module\Supplier\Application\Response\ServiceContractDto;
use Module\Supplier\Application\Response\ServiceDto;
use Module\Supplier\Application\Response\ServicePriceDto;
use Module\Supplier\Application\Response\SupplierDto;

interface SupplierAdapterInterface
{
    public function find(int $id): SupplierDto;

    public function findService(int $id): ?ServiceDto;

    public function getTransferServicePrice(
        int $supplierId,
        int $serviceId,
        int $carId,
        CurrencyEnum $grossCurrency,
        CarbonInterface $date
    ): ?ServicePriceDto;

    public function findTransferServiceContract(int $serviceId): ?ServiceContractDto;

    public function getTransferCancelConditions(): CancelConditionsDto;

    public function getAirportServicePrice(
        int $supplierId,
        int $serviceId,
        int $airportId,
        CurrencyEnum $grossCurrency,
        CarbonInterface $date
    ): ?ServicePriceDto;

    public function findAirportServiceContract(int $serviceId): ?ServiceContractDto;

    public function getAirportCancelConditions(): CancelConditionsDto;
}
