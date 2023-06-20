<?php

namespace Module\Booking\PriceCalculator\Domain\Service\HotelBooking\Support;

use DateTime;

class BookingDaysCollection implements \Iterator
{
    private array $items;

    private int $position = 0;

    public function __construct(array $items)
    {
        $this->validateItems($items);
        $this->items = $items;
    }

    public function nightsCount(): int
    {
        return max(1, count($this->items) - 1);
    }

    public function current(): DateTime
    {
        return $this->items[$this->position];
    }

    public function next(): void
    {
        $this->position++;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    private function validateItems(array $items)
    {
    }
}
