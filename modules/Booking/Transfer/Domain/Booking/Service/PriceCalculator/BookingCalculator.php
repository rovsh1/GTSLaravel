<?php

declare(strict_types=1);

namespace Module\Booking\Transfer\Domain\Booking\Service\PriceCalculator;

use Module\Booking\Transfer\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Transfer\Domain\Booking\Booking;
use Module\Booking\Transfer\Domain\Booking\Service\PriceCalculator\Support\ServicePriceFetcher;
use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\Service\BookingCalculatorInterface;
use Module\Booking\Common\Domain\ValueObject\PriceItem;
use Module\Booking\Order\Domain\Repository\OrderRepositoryInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class BookingCalculator implements BookingCalculatorInterface
{
    public function __construct(
        private readonly ServicePriceFetcher $priceFetcher,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly SupplierAdapterInterface $supplierAdapter,
    ) {}

    public function calculateGrossPrice(BookingInterface|Booking $booking): PriceItem
    {
        return new PriceItem(
            currency: $booking->price()->grossPrice()->currency(),
            calculatedValue: $this->calculate($booking, 'grossPrice'),
            manualValue: null,
            penaltyValue: null,
        );
    }

    public function calculateNetPrice(BookingInterface|Booking $booking): PriceItem
    {
        return new PriceItem(
            currency: $booking->price()->netPrice()->currency(),
            calculatedValue: $this->calculate($booking, 'netPrice'),
            manualValue: null,
            penaltyValue: null,
        );
    }

    private function calculate(Booking $booking, string $priceType): float
    {
        $order = $this->orderRepository->find($booking->orderId()->value());
        if ($order === null) {
            throw new EntityNotFoundException('Order not found');
        }
        $supplier = $this->supplierAdapter->find($booking->serviceInfo()->supplierId());
        if ($supplier === null) {
            throw new EntityNotFoundException('Suppler not found');
        }

        $price = $this->priceFetcher->fetch(
            $booking->serviceInfo()->supplierId(),
            $booking->serviceInfo()->id(),
            $booking->airportInfo()->id(),
            $booking->date(),
            $order->currency(),
            $supplier->currency
        );

        //@todo как тут считать цену?
        return $price->$priceType;
    }
}
