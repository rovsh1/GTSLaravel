<?php

namespace Sdk\Booking\ValueObject;

use Sdk\Module\Support\AbstractValueObjectCollection;

/**
 * @extends AbstractValueObjectCollection<int, CarBid>
 */
class CarBidCollection extends AbstractValueObjectCollection
{
    protected function validateItem(mixed $item): void
    {
        if (!$item instanceof CarBid) {
            throw new \InvalidArgumentException(CarBid::class . ' required');
        }
    }

    public function find(CarId $id): ?CarBid
    {
        foreach ($this->items as $carBid) {
            if ($carBid->carId()->isEqual($id)) {
                return $carBid;
            }
        }

        return null;
    }

    public function hasCar(CarId $id): bool
    {
        return (bool)$this->find($id);
    }

    public function toData(): array
    {
        return $this->map(fn(CarBid $carBid) => $carBid->serialize());
    }

    public static function fromData(array $data): static
    {
        $carBids = array_map(fn(array $item) => CarBid::deserialize($item), $data);

        return (new static($carBids));
    }
}
