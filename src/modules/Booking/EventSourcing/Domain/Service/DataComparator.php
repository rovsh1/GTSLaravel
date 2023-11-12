<?php

namespace Module\Booking\EventSourcing\Domain\Service;

class DataComparator
{
    private array $diff = [];

    public function compare(array $dataA, array $dataB): array
    {
        $this->compareStringAttribute('note', $dataA['note'], $dataB['note']);

        return $this->diff;
    }

    private function compareStringAttribute(string $name, ?string $a, ?string $b): void
    {
        if ($a !== $b) {
            $this->diff[] = new AttributeDiff($name, AttributeTypeEnum::STRING, $a, $b);
        }
    }
}