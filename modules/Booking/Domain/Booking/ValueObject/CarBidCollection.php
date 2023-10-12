<?php

namespace Module\Booking\Domain\Booking\ValueObject;

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

    public function hasCar(CarId $id): bool
    {
        foreach ($this->items as $carBid) {
            if ($carBid->carId()->isEqual($id)) {
                return true;
            }
        }

        return false;
    }

    public function toData(): array
    {
        return $this->map(fn(CarBid $carBid) => $carBid->toData());
    }

    public static function fromData(array $data): static
    {
        $carBids = array_map(fn(array $item) => CarBid::fromData($item), $data);

        return (new static($carBids));
    }
}
