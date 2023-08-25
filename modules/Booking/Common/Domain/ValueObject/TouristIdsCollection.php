<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\ValueObject;

use Illuminate\Support\Collection;

/**
 * @extends Collection<int, TouristId>
 */
class TouristIdsCollection extends Collection
{
    private static string $type = TouristId::class;

    public function __construct($items = [])
    {
        parent::__construct($items);
        $this->ensure($this::$type);
    }

    public function add($item)
    {
        $this->validateValue($item);

        return parent::add($item);
    }

    private function validateValue(mixed $item): void
    {
        $itemType = get_debug_type($item);

        if ($itemType !== $this::$type && !$item instanceof $this::$type) {
            throw new \InvalidArgumentException('Invalid value type');
        }
    }
}
