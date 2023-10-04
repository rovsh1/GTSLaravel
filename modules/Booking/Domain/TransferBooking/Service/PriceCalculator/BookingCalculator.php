<?php

declare(strict_types=1);

namespace Module\Booking\Domain\TransferBooking\Service\PriceCalculator;

use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Domain\Shared\Service\BookingCalculatorInterface;
use Module\Booking\Domain\Shared\ValueObject\PriceItem;
use Module\Booking\Domain\TransferBooking\Adapter\SupplierAdapterInterface;
use Module\Booking\Domain\TransferBooking\TransferBooking;
use Module\Booking\Domain\TransferBooking\Service\PriceCalculator\Support\ServicePriceFetcher;
use Module\Booking\Domain\Order\Repository\OrderRepositoryInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class BookingCalculator implements BookingCalculatorInterface
{
    public function __construct(
        private readonly ServicePriceFetcher $priceFetcher,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly SupplierAdapterInterface $supplierAdapter,
    ) {}

    public function calculateGrossPrice(BookingInterface|TransferBooking $booking): PriceItem
    {
        return new PriceItem(
            currency: $booking->price()->grossPrice()->currency(),
            calculatedValue: $this->calculate($booking, 'grossPrice'),
            manualValue: null,
            penaltyValue: null,
        );
    }

    public function calculateNetPrice(BookingInterface|TransferBooking $booking): PriceItem
    {
        return new PriceItem(
            currency: $booking->price()->netPrice()->currency(),
            calculatedValue: $this->calculate($booking, 'netPrice'),
            manualValue: null,
            penaltyValue: null,
        );
    }

    private function calculate(TransferBooking $booking, string $priceType): float
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
