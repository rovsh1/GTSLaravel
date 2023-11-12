<?php

namespace Module\Booking\EventSourcing\Domain\Service;

class BookingDiff
{
    /**
     * @param AttributeDiff[] $modifiedAttributes
     */
    public function __construct(private readonly array $modifiedAttributes)
    {
    }

    public function serialize(): array
    {
        return array_map(fn($a) => $a->serialize(), $this->modifiedAttributes);
    }
}