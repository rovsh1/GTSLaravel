<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Domain\Adapter;

use Carbon\CarbonInterface;
use Module\Shared\Enum\CurrencyEnum;
use Module\Supplier\Application\Response\ServicePriceDto;
use Module\Supplier\Application\Response\SupplierDto;

interface SupplierAdapterInterface
{
    public function getAirportServicePrice(
        int $supplierId,
        int $serviceId,
        int $airportId,
        CurrencyEnum $netCurrency,
        CurrencyEnum $grossCurrency,
        CarbonInterface $date
    ): ?ServicePriceDto;

    public function find(int $id): SupplierDto;
}