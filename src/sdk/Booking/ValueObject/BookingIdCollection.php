<?php

declare(strict_types=1);

namespace Sdk\Booking\ValueObject;


use Sdk\Module\Support\AbstractValueObjectCollection;
use Sdk\Shared\Contracts\Support\SerializableInterface;

/**
 * @extends AbstractValueObjectCollection<int, GuestId>
 */
class BookingIdCollection extends AbstractValueObjectCollection implements SerializableInterface
{
    protected function validateItem(mixed $item): void
    {
        if (!$item instanceof BookingId) {
            throw new \InvalidArgumentException(BookingId::class . ' instance required');
        }
    }

    public function serialize(): array
    {
        return $this->map(fn(BookingId $id) => $id->value());
    }

    public static function deserialize(array $payload): static
    {
        $ids = array_map(fn(int $id) => new BookingId($id), $payload);

        return (new static($ids));
    }
}
