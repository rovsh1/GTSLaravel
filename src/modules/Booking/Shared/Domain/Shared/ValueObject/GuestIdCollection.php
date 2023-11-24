<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Shared\ValueObject;


use Module\Booking\Shared\Domain\Guest\ValueObject\GuestId;
use Module\Shared\Contracts\Support\SerializableInterface;
use Sdk\Module\Support\AbstractValueObjectCollection;

/**
 * @extends AbstractValueObjectCollection<int, GuestId>
 */
class GuestIdCollection extends AbstractValueObjectCollection implements SerializableInterface
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

    public function serialize(): array
    {
        return $this->map(fn(GuestId $id) => $id->value());
    }

    public static function deserialize(array $payload): static
    {
        $ids = array_map(fn(int $id) => new GuestId($id), $payload);

        return (new static($ids));
    }
}
