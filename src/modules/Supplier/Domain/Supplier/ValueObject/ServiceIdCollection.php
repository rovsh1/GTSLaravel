<?php

declare(strict_types=1);

namespace Module\Supplier\Domain\Supplier\ValueObject;

use Sdk\Module\Support\AbstractValueObjectCollection;

class ServiceIdCollection extends AbstractValueObjectCollection
{
    protected function validateItem(mixed $item): void
    {
        if (!$item instanceof ServiceId) {
            throw new \InvalidArgumentException(ServiceId::class . ' required');
        }
    }

    public function toData(): array
    {
        return $this->map(fn(ServiceId $id) => $id->value());
    }

    public static function fromData(array $data): static
    {
        $ids = array_map(fn(int $id) => new ServiceId($id), $data);

        return (new static($ids));
    }
}
