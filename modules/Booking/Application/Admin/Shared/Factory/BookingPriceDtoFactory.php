<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\Shared\Factory;

use Module\Booking\Application\Admin\Shared\Response\BookingPriceDto;
use Module\Booking\Application\Admin\Shared\Response\PriceItemDto;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingPrice;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingPriceItem;
use Module\Shared\Contracts\Adapter\CurrencyRateAdapterInterface;
use Module\Shared\Contracts\Service\TranslatorInterface;
use Module\Shared\Dto\CurrencyDto;

class BookingPriceDtoFactory
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly CurrencyRateAdapterInterface $currencyRateAdapter,
    ) {
    }

    public function createFromEntity(BookingPrice $entity): BookingPriceDto
    {
        $clientPriceItem = $entity->clientPrice();
        $supplierPriceItem = $entity->supplierPrice();

        $grossPriceDto = $this->makePriceItemDto($clientPriceItem);
        $netPriceDto = $this->makePriceItemDto($supplierPriceItem);

        $profit = new PriceItemDto(
            CurrencyDto::fromEnum($clientPriceItem->currency(), $this->translator),
            $this->calculateProfitValue($supplierPriceItem, $clientPriceItem),
            null,
            null,
            false
        );

        return new BookingPriceDto($netPriceDto, $grossPriceDto, $profit);
    }

    private function makePriceItemDto(BookingPriceItem $priceItem): PriceItemDto
    {
        return new PriceItemDto(
            CurrencyDto::fromEnum($priceItem->currency(), $this->translator),
            $priceItem->calculatedValue(),
            $priceItem->manualValue(),
            $priceItem->penaltyValue(),
            $priceItem->manualValue() !== null,
        );
    }

    private function calculateProfitValue(BookingPriceItem $supplierPriceItem, BookingPriceItem $clientPriceItem): float
    {
        $netPriceValue = $supplierPriceItem->manualValue() ?? $supplierPriceItem->calculatedValue();
        $grossPriceValue = $clientPriceItem->manualValue() ?? $clientPriceItem->calculatedValue();

        if ($clientPriceItem->currency() === $supplierPriceItem->currency()) {
            return $grossPriceValue - $netPriceValue;
        }

        $netConvertedValue = $this->currencyRateAdapter->convertNetRate(
            $netPriceValue,
            $supplierPriceItem->currency(),
            $clientPriceItem->currency(),
            'UZ'
        );

        return $grossPriceValue - $netConvertedValue;
    }
}
