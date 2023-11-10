<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking;


use Sdk\Module\Support\AbstractValueObjectCollection;

/**
 * @extends AbstractValueObjectCollection<int, AccommodationId>
 */
class AccommodationIdCollection extends AbstractValueObjectCollection
{
    protected function validateItem(mixed $item): void
    {
        if (!$item instanceof AccommodationId) {
            throw new \InvalidArgumentException(AccommodationId::class . ' instance required');
        }
    }

    public function has(AccommodationId $id): bool
    {
        foreach ($this->items as $roomId) {
            if ($roomId->isEqual($id)) {
                return true;
            }
        }

        return false;
    }

    public function toData(): array
    {
        return $this->map(fn(AccommodationId $id) => $id->value());
    }

    public static function fromData(array $data): static
    {
        $ids = array_map(fn(int $id) => new AccommodationId($id), $data);

        return (new static($ids));
    }
}
