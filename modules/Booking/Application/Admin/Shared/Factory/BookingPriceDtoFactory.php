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
use Module\Shared\Enum\CurrencyEnum;

class BookingPriceDtoFactory
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly CurrencyRateAdapterInterface $currencyRateAdapter,
    ) {}

    public function createFromEntity(BookingPrice $entity): BookingPriceDto
    {
        $clientPriceItem = $entity->clientPrice();
        $supplierPriceItem = $entity->supplierPrice();

        $grossPriceDto = $this->makePriceItemDto($clientPriceItem);
        $netPriceDto = $this->makePriceItemDto($supplierPriceItem);
        $convertedNetPriceDto = $this->getConvertedNetPrice($supplierPriceItem, $clientPriceItem->currency());
        $convertedNetPriceValue = $convertedNetPriceDto?->calculatedValue ?? $netPriceDto->manualValue ?? $netPriceDto->calculatedValue;

        $profit = new PriceItemDto(
            CurrencyDto::fromEnum($clientPriceItem->currency(), $this->translator),
            $this->calculateProfitValue($supplierPriceItem, $convertedNetPriceValue),
            null,
            null,
            false
        );


        return new BookingPriceDto($netPriceDto, $grossPriceDto, $profit, $convertedNetPriceDto);
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

    private function getConvertedNetPrice(BookingPriceItem $supplierPriceItem, CurrencyEnum $outCurrency): ?PriceItemDto
    {
        $netPriceValue = $supplierPriceItem->manualValue() ?? $supplierPriceItem->calculatedValue();
        if ($supplierPriceItem->currency() === $outCurrency) {
            return null;
        }

        $netConvertedValue = $this->currencyRateAdapter->convertNetRate(
            $netPriceValue,
            $supplierPriceItem->currency(),
            $outCurrency,
            'UZ'
        );

        return new PriceItemDto(
            currency: CurrencyDto::fromEnum($outCurrency, $this->translator),
            calculatedValue: $netConvertedValue,
            manualValue: null,
            penaltyValue: null,
            isManual: false,
        );
    }

    private function calculateProfitValue(BookingPriceItem $clientPriceItem, float $netConvertedValue): float
    {
        $grossPriceValue = $clientPriceItem->manualValue() ?? $clientPriceItem->calculatedValue();

        return $grossPriceValue - $netConvertedValue;
    }
}
