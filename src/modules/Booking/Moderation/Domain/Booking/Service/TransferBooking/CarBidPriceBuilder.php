<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Booking\Service\TransferBooking;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Exception\NotFoundTransferServicePrice;
use Sdk\Booking\ValueObject\CarBid\CarBidPriceItem;
use Sdk\Booking\ValueObject\CarBid\CarBidPrices;
use Sdk\Shared\Enum\CurrencyEnum;

class CarBidPriceBuilder
{
    public function __construct(
        private readonly SupplierAdapterInterface $supplierAdapter
    ) {}

    public function build(
        int $supplierId,
        int $serviceId,
        int $carId,
        CurrencyEnum $clientCurrency,
        DateTimeInterface $date
    ): CarBidPrices {
        $price = $this->supplierAdapter->getTransferServicePrice(
            $supplierId,
            $serviceId,
            $carId,
            $clientCurrency,
            $date
        );
        if ($price === null) {
            throw new NotFoundTransferServicePrice();
        }

        $supplierPrice = $price->supplierPrice;
        $clientPrice = $price->clientPrice;

        return new CarBidPrices(
            supplierPrice: new CarBidPriceItem($supplierPrice->currency, $supplierPrice->amount),
            clientPrice: new CarBidPriceItem($clientPrice->currency, $clientPrice->amount)
        );
    }
}
