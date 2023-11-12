<?php

namespace Module\Booking\EventSourcing\Domain\Service\BookingComparator;

class AttributeDiff
{
    public function __construct(
        private readonly string $name,
        private readonly AttributeTypeEnum $type,
        private readonly mixed $valueBefore,
        private readonly mixed $valueAfter
    ) {
    }

    public function serialize(): array
    {
        return [
            'name' => $this->name,
            'before' => $this->cast($this->valueBefore),
            'after' => $this->cast($this->valueAfter),
        ];
    }

    private function cast($value)
    {
        if ($value === null) {
            return null;
        }

        return match ($this->type) {
            AttributeTypeEnum::BOOLEAN => (bool)$value,
            AttributeTypeEnum::FLOAT => (float)$value,
            AttributeTypeEnum::INTEGER => (int)$value,
            AttributeTypeEnum::DATE => $value->format('Y-m-d H:i:s'),
            default => $value,
        };
    }
}