<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Shared\ValueObject;


use Module\Shared\Contracts\Support\SerializableDataInterface;
use Sdk\Module\Support\AbstractValueObjectCollection;

/**
 * @extends AbstractValueObjectCollection<int, GuestId>
 */
class GuestIdCollection extends AbstractValueObjectCollection implements SerializableDataInterface
{
    protected function validateItem(mixed $item): void
    {
        if (!$item instanceof GuestId) {
            throw new \InvalidArgumentException(GuestId::class . ' instance required');
        }
    }

    public function has(GuestId $id): bool
    {
        foreach ($this->items as $guestId) {
            if ($guestId->isEqual($id)) {
                return true;
            }
        }

        return false;
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
