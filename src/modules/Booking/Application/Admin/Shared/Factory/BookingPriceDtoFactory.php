<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\Shared\Factory;

use Module\Booking\Application\Admin\Shared\Response\BookingPriceDto;
use Module\Booking\Application\Admin\Shared\Response\PriceItemDto;
use Module\Booking\Domain\Booking\ValueObject\BookingPriceItem;
use Module\Booking\Domain\Booking\ValueObject\BookingPrices;
use Module\Shared\Contracts\Adapter\CurrencyRateAdapterInterface;
use Module\Shared\Contracts\Service\TranslatorInterface;
use Module\Shared\Dto\CurrencyDto;
use Module\Shared\Enum\CurrencyEnum;

class BookingPriceDtoFactory
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly CurrencyRateAdapterInterface $currencyRateAdapter,
    ) {
    }

    public function createFromEntity(BookingPrices $entity): BookingPriceDto
    {
        $clientPriceItem = $entity->clientPrice();
        $supplierPriceItem = $entity->supplierPrice();

        $clientPriceDto = $this->makePriceItemDto($clientPriceItem);
        $supplierPriceDto = $this->makePriceItemDto($supplierPriceItem);
        $convertedSupplierPriceDto = $this->getConvertedSupplierPrice($supplierPriceItem, $clientPriceItem->currency());
        $convertedSupplierPriceValue = $convertedSupplierPriceDto?->calculatedValue ?? $supplierPriceDto->manualValue ?? $supplierPriceDto->calculatedValue;

        $profit = new PriceItemDto(
            CurrencyDto::fromEnum($clientPriceItem->currency(), $this->translator),
            $this->calculateProfitValue($clientPriceItem, $convertedSupplierPriceValue, $convertedSupplierPriceDto->penaltyValue),
            null,
            null,
            false
        );


        return new BookingPriceDto($supplierPriceDto, $clientPriceDto, $profit, $convertedSupplierPriceDto);
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

    private function getConvertedSupplierPrice(
        BookingPriceItem $supplierPriceItem,
        CurrencyEnum $outCurrency
    ): ?PriceItemDto {
        $netPriceValue = $supplierPriceItem->manualValue() ?? $supplierPriceItem->calculatedValue();
        if ($supplierPriceItem->currency() === $outCurrency) {
            return new PriceItemDto(
                currency: CurrencyDto::fromEnum($outCurrency, $this->translator),
                calculatedValue: $netPriceValue,
                manualValue: null,
                penaltyValue: $supplierPriceItem->penaltyValue(),
                isManual: false,
            );
        }

        $convertedValue = $this->currencyRateAdapter->convertNetRate(
            $netPriceValue,
            $supplierPriceItem->currency(),
            $outCurrency,
            'UZ'
        );

        $convertedPenaltyValue = 0;
        if ($supplierPriceItem->penaltyValue() !== null) {
            $convertedPenaltyValue = $this->currencyRateAdapter->convertNetRate(
                $supplierPriceItem->penaltyValue(),
                $supplierPriceItem->currency(),
                $outCurrency,
                'UZ'
            );
        }

        return new PriceItemDto(
            currency: CurrencyDto::fromEnum($outCurrency, $this->translator),
            calculatedValue: $convertedValue,
            manualValue: null,
            penaltyValue: $convertedPenaltyValue,
            isManual: false,
        );
    }

    private function calculateProfitValue(
        BookingPriceItem $clientPriceItem,
        float $convertedSupplierValue,
        ?float $convertedSupplierPenaltyValue,
    ): float {
        $clientPriceValue = ($clientPriceItem->manualValue() ?? $clientPriceItem->calculatedValue()) - $clientPriceItem->penaltyValue();

        return $clientPriceValue - ($convertedSupplierValue - $convertedSupplierPenaltyValue);
    }
}
