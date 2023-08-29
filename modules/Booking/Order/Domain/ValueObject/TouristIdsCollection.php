<?php

declare(strict_types=1);

namespace Module\Booking\Order\Domain\ValueObject;


use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Sdk\Module\Support\AbstractValueObjectCollection;

/**
 * @extends AbstractValueObjectCollection<int, TouristId>
 */
class TouristIdsCollection extends AbstractValueObjectCollection implements SerializableDataInterface
{
    protected function validateItem(mixed $item): void
    {
        if (!$item instanceof TouristId) {
            throw new \InvalidArgumentException(TouristId::class . ' instance required');
        }
    }

    public function toData(): array
    {
        return $this->map(fn(TouristId $id) => $id->value());
    }

    public static function fromData(array $data): static
    {
        $ids = array_map(fn(int $id) => new TouristId($id), $data);
        return (new static($ids));
    }
}
