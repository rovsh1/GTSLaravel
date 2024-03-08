<?php

declare(strict_types=1);

namespace Module\Hotel\Quotation\Application\Service\Factory;

use Module\Hotel\Quotation\Application\Service\Supplier\Gotostans;
use Module\Hotel\Quotation\Application\Service\Supplier\Traveline;
use Module\Hotel\Quotation\Application\Service\SupplierQuotaBookerInterface;
use Module\Hotel\Quotation\Application\Service\SupplierQuotaFetcherInterface;
use Module\Hotel\Quotation\Application\Service\SupplierQuotaUpdaterInterface;
use Sdk\Module\Contracts\Support\ContainerInterface;
use Shared\Contracts\Adapter\TravelineAdapterInterface;

class SupplierFactory
{
    public function __construct(
        private readonly TravelineAdapterInterface $travelineAdapter,
        private readonly ContainerInterface $container,
    ) {}

    public function fetcher(int $hotelId): SupplierQuotaFetcherInterface
    {
        return $this->getSupplier($hotelId);
    }

    public function updater(int $hotelId): SupplierQuotaUpdaterInterface
    {
        return $this->getSupplier($hotelId);
    }

    public function booker(int $hotelId): SupplierQuotaBookerInterface
    {
        return $this->getSupplier($hotelId);
    }

    private function getSupplier(int $hotelId): SupplierQuotaFetcherInterface|SupplierQuotaUpdaterInterface|SupplierQuotaBookerInterface {
        if ($this->travelineAdapter->isHotelIntegrationEnabled($hotelId)) {
            return $this->container->make(Traveline::class);
        }

        return $this->container->make(Gotostans::class);
    }
}
