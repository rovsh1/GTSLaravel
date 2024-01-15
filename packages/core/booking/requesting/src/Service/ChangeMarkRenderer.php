<?php

namespace Pkg\Booking\Requesting\Service;

use Module\Booking\Shared\Domain\Booking\Booking;
use Pkg\Booking\Requesting\Domain\Service\ChangesStorageInterface;
use Pkg\Booking\Requesting\Domain\ValueObject\ChangesIdentifier;

class ChangeMarkRenderer
{
    private Booking $booking;

    private bool $changedFlag = false;

    public function __construct(
        private readonly ChangesStorageInterface $changesStorage
    ) {}

    public function boot(Booking $booking): void
    {
        $this->booking = $booking;
    }

    public function reset(): void
    {
        unset($this->booking);
    }

    public function changed(string $field): bool
    {
        return $this->changedFlag = $this->changesStorage->exists(
            new ChangesIdentifier(
                $this->booking->id()->value(),
                $field
            )
        );
    }

    public function endChanged(): bool
    {
        return $this->changedFlag;
    }
}