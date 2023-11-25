<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Factory;

use Module\Booking\Moderation\Application\Dto\BookingPriceDto;
use Module\Booking\Moderation\Application\Dto\PriceItemDto;
use Module\Booking\Moderation\Application\Dto\ProfitItemDto;
use Sdk\Booking\ValueObject\BookingPriceItem;
use Sdk\Booking\ValueObject\BookingPrices;
use Sdk\Shared\Contracts\Adapter\CurrencyRateAdapterInterface;
use Sdk\Shared\Contracts\Service\TranslatorInterface;
use Sdk\Shared\Dto\CurrencyDto;
use Sdk\Shared\Enum\CurrencyEnum;

class BookingPriceDtoFactory
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly CurrencyRateAdapterInterface $currencyRateAdapter,
    ) {}

    public function createFromEntity(BookingPrices $entity): BookingPriceDto
    {
        $clientPriceItem = $entity->clientPrice();
        $supplierPriceItem = $entity->supplierPrice();

        $clientPriceDto = $this->makePriceItemDto($clientPriceItem);
        $supplierPriceDto = $this->makePriceItemDto($supplierPriceItem);
        $convertedSupplierPriceDto = $this->getConvertedSupplierPrice($supplierPriceItem, $clientPriceItem->currency());

        $calculatedClientValue = $this->calculateClientValue($clientPriceItem);
        $calculatedSupplierValue = $this->calculateSupplierValue($convertedSupplierPriceDto->calculatedValue, $convertedSupplierPriceDto->penaltyValue);
        $profit = new ProfitItemDto(
            CurrencyDto::fromEnum($clientPriceItem->currency(), $this->translator),
            $calculatedSupplierValue,
            $calculatedClientValue,
            $this->calculateProfitValue($calculatedClientValue, $calculatedSupplierValue),
        );


        return new BookingPriceDto($supplierPriceDto, $clientPriceDto, $profit);
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
    ): PriceItemDto {
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

    private function calculateClientValue(BookingPriceItem $clientPriceItem): float
    {
        return ($clientPriceItem->manualValue() ?? $clientPriceItem->calculatedValue(
            )) - $clientPriceItem->penaltyValue();
    }

    private function calculateSupplierValue(float $convertedSupplierValue, ?float $convertedSupplierPenaltyValue): float
    {
        return ($convertedSupplierValue - $convertedSupplierPenaltyValue);
    }

    private function calculateProfitValue(float $calculatedClientValue, float $calculatedSupplierValue,): float
    {
        return $calculatedClientValue - $calculatedSupplierValue;
    }
}
