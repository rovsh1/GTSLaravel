<?php

declare(strict_types=1);

namespace Module\Booking\Order\Domain\ValueObject;


use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Sdk\Module\Support\AbstractValueObjectCollection;

/**
 * @extends AbstractValueObjectCollection<int, GuestId>
 */
class GuestIdsCollection extends AbstractValueObjectCollection implements SerializableDataInterface
{
    protected function validateItem(mixed $item): void
    {
        if (!$item instanceof GuestId) {
            throw new \InvalidArgumentException(GuestId::class . ' instance required');
        }
    }

    public function toData(): array
    {
        return $this->map(fn(GuestId $id) => $id->value());
    }

    public static function fromData(array $data): static
    {
        $ids = array_map(fn(int $id) => new GuestId($id), $data);
        return (new static($ids));
    }
}
