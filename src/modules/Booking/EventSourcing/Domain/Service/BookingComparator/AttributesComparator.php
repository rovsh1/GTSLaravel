<?php

namespace Module\Booking\EventSourcing\Domain\Service\BookingComparator;

class AttributesComparator
{
    private array $diff = [];

    private array $attributes = [
        'note' => AttributeTypeEnum::STRING,
        'prices.supplierPrice.calculatedValue' => AttributeTypeEnum::FLOAT,
        'prices.supplierPrice.manualValue' => AttributeTypeEnum::FLOAT,
        'prices.supplierPrice.penaltyValue' => AttributeTypeEnum::FLOAT,
        'prices.supplierPrice.currency' => AttributeTypeEnum::STRING,
        'prices.clientPrice.calculatedValue' => AttributeTypeEnum::FLOAT,
        'prices.clientPrice.manualValue' => AttributeTypeEnum::FLOAT,
        'prices.clientPrice.penaltyValue' => AttributeTypeEnum::FLOAT,
        'prices.clientPrice.currency' => AttributeTypeEnum::STRING,
    ];

    public function compare(array $dataA, array $dataB): array
    {
        foreach ($this->attributes as $path => $type) {
            $this->compareAttribute($path, $type, $dataA, $dataB);
        }

        return $this->diff;
    }

    private function compareAttribute(string $path, AttributeTypeEnum $type, array $a, array $b): void
    {
        $valueA = $this->getValueByPath($a, $path);
        $valueB = $this->getValueByPath($b, $path);
        if ($valueA !== $valueB) {
            $this->diff[] = new AttributeDiff($path, $type, $valueA, $valueB);
        }
    }

    private function getValueByPath(array $data, string $path): mixed
    {
        $value = $data;
        foreach (explode('.', $path) as $k) {
            if (!isset($value[$k])) {
                return null;
            }
            $value = $value[$k];
        }

        return $value;
    }
}