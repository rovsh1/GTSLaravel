<?php

namespace Module\Booking\Moderation\Domain\Booking\Service\ChangeHistory;

class AttributeDiff implements ChangesInterface
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

        switch ($this->type) {
            case 'bool':
                return (bool)$value;
            case 'float':
                return (float)$value;
            case 'int':
                return (int)$value;
            case 'datetime':
                return $value->format('Y-m-d H:i:s');
            default:
                return $value;
        }
    }
}